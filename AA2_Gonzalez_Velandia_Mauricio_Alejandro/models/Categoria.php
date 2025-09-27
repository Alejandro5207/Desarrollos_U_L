<?php
require_once __DIR__ . '/../config/database.php';

class Categoria {
    private $conn;
    private $table_name = "categorias";

    public $id;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $activo;
    public $fecha_creacion;
    public $fecha_actualizacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT c.*, COUNT(p.id) as productos_count
                  FROM " . $this->table_name . " c
                  LEFT JOIN productos p ON c.id = p.categoria_id AND p.activo = 1
                  WHERE c.activo = 1
                  GROUP BY c.id
                  ORDER BY c.nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, descripcion, icono, color, activo) 
                  VALUES (:nombre, :descripcion, :icono, :color, :activo)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':icono', $data['icono']);
        $stmt->bindParam(':color', $data['color']);
        $stmt->bindParam(':activo', $data['activo']);
        
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, 
                      descripcion = :descripcion, 
                      icono = :icono,
                      color = :color,
                      activo = :activo
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':icono', $data['icono']);
        $stmt->bindParam(':color', $data['color']);
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

    public function getWithProductCount() {
        $query = "SELECT c.*, COUNT(p.id) as total_productos
                  FROM " . $this->table_name . " c
                  LEFT JOIN productos p ON c.id = p.categoria_id AND p.activo = 1
                  WHERE c.activo = 1
                  GROUP BY c.id
                  ORDER BY c.nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_categorias,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as categorias_activas
                  FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch();
    }
}
?>
