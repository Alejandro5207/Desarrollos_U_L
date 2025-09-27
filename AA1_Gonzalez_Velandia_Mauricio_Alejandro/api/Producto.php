<?php
require_once '../config/database.php';

class Producto {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $categoria_id;
    public $imagen_url;
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
                  WHERE p.activo = 1 
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.id = :id AND p.activo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, descripcion, precio, stock, categoria_id, imagen_url, activo) 
                  VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :imagen_url, :activo)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = floatval($this->precio);
        $this->stock = intval($this->stock);
        $this->categoria_id = intval($this->categoria_id);
        $this->imagen_url = htmlspecialchars(strip_tags($this->imagen_url));
        $this->activo = $this->activo ? 1 : 0;
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
        $stmt->bindParam(':imagen_url', $this->imagen_url);
        $stmt->bindParam(':activo', $this->activo);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, 
                      descripcion = :descripcion, 
                      precio = :precio, 
                      stock = :stock, 
                      categoria_id = :categoria_id, 
                      imagen_url = :imagen_url, 
                      activo = :activo,
                      fecha_actualizacion = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = floatval($this->precio);
        $this->stock = intval($this->stock);
        $this->categoria_id = intval($this->categoria_id);
        $this->imagen_url = htmlspecialchars(strip_tags($this->imagen_url));
        $this->activo = $this->activo ? 1 : 0;
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
        $stmt->bindParam(':imagen_url', $this->imagen_url);
        $stmt->bindParam(':activo', $this->activo);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function delete() {
        $query = "UPDATE " . $this->table_name . " 
                  SET activo = 0, fecha_actualizacion = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function deletePermanent() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function search($searchTerm) {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.activo = 1 AND (p.nombre LIKE :search OR p.descripcion LIKE :search)
                  ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>