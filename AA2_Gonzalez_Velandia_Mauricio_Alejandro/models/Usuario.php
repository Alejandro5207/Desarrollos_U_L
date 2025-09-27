<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $direccion;
    public $rol;
    public $activo;
    public $fecha_registro;
    public $fecha_actualizacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT id, nombre, apellido, email, telefono, direccion, rol, activo, fecha_registro 
                  FROM " . $this->table_name . " 
                  ORDER BY fecha_registro DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT id, nombre, apellido, email, telefono, direccion, rol, activo, fecha_registro 
                  FROM " . $this->table_name . " 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, apellido, email, password, telefono, direccion, rol, activo) 
                  VALUES (:nombre, :apellido, :email, :password, :telefono, :direccion, :rol, :activo)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':rol', $data['rol']);
        $stmt->bindParam(':activo', $data['activo']);
        
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre = :nombre, 
                      apellido = :apellido, 
                      email = :email, 
                      telefono = :telefono,
                      direccion = :direccion,
                      rol = :rol,
                      activo = :activo
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':rol', $data['rol']);
        $stmt->bindParam(':activo', $data['activo']);
        $stmt->bindParam(':id', $id);
        
        if (isset($data['password']) && !empty($data['password'])) {
            $passwordQuery = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
            $passwordStmt = $this->conn->prepare($passwordQuery);
            $passwordStmt->bindParam(':password', $data['password']);
            $passwordStmt->bindParam(':id', $id);
            $passwordStmt->execute();
        }
        
        return $stmt->execute();
    }

    public function updatePassword() {
        $query = "UPDATE " . $this->table_name . " 
                  SET password = :password, 
                      fecha_actualizacion = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_usuarios,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as usuarios_activos,
                    SUM(CASE WHEN rol = 'admin' THEN 1 ELSE 0 END) as usuarios_admin,
                    SUM(CASE WHEN rol = 'usuario' THEN 1 ELSE 0 END) as usuarios_normales
                  FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getRecent($limit = 5) {
        $query = "SELECT id, nombre, apellido, email, telefono, direccion, rol, activo, fecha_registro 
                  FROM " . $this->table_name . " 
                  WHERE activo = 1
                  ORDER BY fecha_registro DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>
