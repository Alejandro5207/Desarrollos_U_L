<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $precio_oferta;
    public $stock;
    public $categoria_id;
    public $imagen_principal;
    public $galeria;
    public $destacado;
    public $activo;
    public $fecha_creacion;
    public $fecha_actualizacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND p.stock > 0 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.id = :id AND p.activo = 1 AND p.stock > 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getDestacados() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND p.destacado = 1 AND p.stock > 0 
                  ORDER BY p.fecha_creacion DESC 
                  LIMIT 8";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getByCategoria($categoria_id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND p.categoria_id = :categoria_id AND p.stock > 0 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function search($searchTerm = '', $categoria_id = '') {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND p.stock > 0";
        
        $params = [];
        
        if (!empty($searchTerm)) {
            $query .= " AND (p.nombre LIKE :search OR p.descripcion LIKE :search)";
            $params[':search'] = '%' . $searchTerm . '%';
        }
        
        if (!empty($categoria_id)) {
            $query .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoria_id;
        }
        
        $query .= " ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getRecent($limit = 5) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND p.stock > 0 
                  ORDER BY p.fecha_creacion DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, descripcion, precio, stock, categoria_id, imagen_principal, destacado, activo) 
                  VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :imagen, :destacado, :activo)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':categoria_id', $data['categoria_id']);
        $stmt->bindParam(':imagen', $data['imagen']);
        $stmt->bindParam(':destacado', $data['destacado']);
        $stmt->bindParam(':activo', $data['activo']);
        
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, 
                      descripcion = :descripcion, 
                      precio = :precio, 
                      stock = :stock, 
                      categoria_id = :categoria_id, 
                      imagen_principal = :imagen,
                      destacado = :destacado,
                      activo = :activo
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':categoria_id', $data['categoria_id']);
        $stmt->bindParam(':imagen', $data['imagen']);
        $stmt->bindParam(':destacado', $data['destacado']);
        $stmt->bindParam(':activo', $data['activo']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function deletePermanent() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_productos,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as productos_activos,
                    SUM(CASE WHEN activo = 0 THEN 1 ELSE 0 END) as productos_inactivos,
                    SUM(CASE WHEN destacado = 1 THEN 1 ELSE 0 END) as productos_destacados,
                    SUM(stock) as stock_total,
                    SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as productos_sin_stock,
                    SUM(CASE WHEN stock > 0 AND stock <= 5 THEN 1 ELSE 0 END) as stock_bajo,
                    AVG(precio) as precio_promedio
                  FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getByActivo($activo) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = :activo 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':activo', $activo);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getByStock($stock) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.stock = :stock 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':stock', $stock);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getByStockRange($min_stock, $max_stock) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.stock >= :min_stock AND p.stock <= :max_stock 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':min_stock', $min_stock);
        $stmt->bindParam(':max_stock', $max_stock);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getAllForAdmin() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getByIdForAdmin($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getDestacadosForAdmin() {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.destacado = 1 
                  ORDER BY p.fecha_creacion DESC 
                  LIMIT 8";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getByCategoriaForAdmin($categoria_id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.categoria_id = :categoria_id 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function searchForAdmin($searchTerm = '', $categoria_id = '') {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE 1=1";
        
        $params = [];
        
        if (!empty($searchTerm)) {
            $query .= " AND (p.nombre LIKE :search OR p.descripcion LIKE :search)";
            $params[':search'] = '%' . $searchTerm . '%';
        }
        
        if (!empty($categoria_id)) {
            $query .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoria_id;
        }
        
        $query .= " ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>
