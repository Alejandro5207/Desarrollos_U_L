<?php
require_once __DIR__ . '/../config/database.php';

class Pedido {
    private $conn;
    private $table_name = "pedidos";

    public $id;
    public $usuario_id;
    public $fecha_pedido;
    public $estado;
    public $total;
    public $metodo_pago;
    public $direccion_entrega;
    public $notas;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT p.*, u.nombre as usuario_nombre, u.apellido as usuario_apellido, 
                         u.email as usuario_email, u.telefono as usuario_telefono
                  FROM " . $this->table_name . " p 
                  LEFT JOIN usuarios u ON p.usuario_id = u.id 
                  ORDER BY p.fecha_pedido DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT p.*, u.nombre as usuario_nombre, u.apellido as usuario_apellido, 
                         u.email as usuario_email, u.telefono as usuario_telefono
                  FROM " . $this->table_name . " p 
                  LEFT JOIN usuarios u ON p.usuario_id = u.id 
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getDetalles($pedido_id) {
        $query = "SELECT pd.*, p.nombre, p.imagen_principal as imagen, p.precio as precio_unitario
                  FROM pedidos_detalle pd 
                  LEFT JOIN productos p ON pd.producto_id = p.id 
                  WHERE pd.pedido_id = :pedido_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pedido_id', $pedido_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (usuario_id, estado, total, metodo_pago, direccion_envio, telefono_contacto, notas) 
                  VALUES (:usuario_id, :estado, :total, :metodo_pago, :direccion_envio, :telefono_contacto, :notas)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':metodo_pago', $data['metodo_pago']);
        $stmt->bindParam(':direccion_envio', $data['direccion_envio']);
        $stmt->bindParam(':telefono_contacto', $data['telefono_contacto']);
        $stmt->bindParam(':notas', $data['notas']);
        
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET usuario_id = :usuario_id, 
                      estado = :estado, 
                      total = :total, 
                      metodo_pago = :metodo_pago,
                      direccion_envio = :direccion_envio,
                      telefono_contacto = :telefono_contacto,
                      notas = :notas
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':metodo_pago', $data['metodo_pago']);
        $stmt->bindParam(':direccion_envio', $data['direccion_envio']);
        $stmt->bindParam(':telefono_contacto', $data['telefono_contacto']);
        $stmt->bindParam(':notas', $data['notas']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function updateEstado($id, $estado) {
        $query = "UPDATE " . $this->table_name . " 
                  SET estado = :estado 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        
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
                    COUNT(*) as total_pedidos,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pedidos_pendientes,
                    SUM(CASE WHEN estado = 'procesando' THEN 1 ELSE 0 END) as pedidos_procesando,
                    SUM(CASE WHEN estado = 'enviado' THEN 1 ELSE 0 END) as pedidos_enviados,
                    SUM(CASE WHEN estado = 'entregado' THEN 1 ELSE 0 END) as pedidos_entregados,
                    SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) as pedidos_cancelados,
                    SUM(total) as ingresos_totales
                  FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getRecent($limit = 5) {
        $query = "SELECT p.*, u.nombre as usuario_nombre, u.apellido as usuario_apellido, u.email as usuario_email
                  FROM " . $this->table_name . " p
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  ORDER BY p.fecha_pedido DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getByUsuario($usuario_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE usuario_id = :usuario_id 
                  ORDER BY fecha_pedido DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>
