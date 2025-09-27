<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';
require_once 'ResponseHandler.php';
require_once 'Producto.php';

$database = new Database();
$db = $database->getConnection();

if ($db === null) {
    ResponseHandler::error('Error de conexión a la base de datos', 500);
}

$producto = new Producto($db);

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$path_info = parse_url($request_uri, PHP_URL_PATH);
$query_string = parse_url($request_uri, PHP_URL_QUERY);
parse_str($query_string, $query_params);

switch ($method) {
    case 'GET':
        if (isset($query_params['id'])) {
            $id = intval($query_params['id']);
            $result = $producto->getById($id);
            
            if ($result) {
                ResponseHandler::success($result, 'Producto encontrado');
            } else {
                ResponseHandler::error('Producto no encontrado', 404);
            }
        } elseif (isset($query_params['search'])) {
            $searchTerm = $query_params['search'];
            $result = $producto->search($searchTerm);
            ResponseHandler::success($result, 'Búsqueda completada');
        } else {
            $result = $producto->getAll();
            ResponseHandler::success($result, 'Productos obtenidos correctamente');
        }
        break;
        
    case 'POST':
        ResponseHandler::validateMethod(['POST']);
        $data = ResponseHandler::getRequestBody();
        
        $requiredFields = ['nombre', 'precio', 'stock'];
        $missing = ResponseHandler::validateRequired($data, $requiredFields);
        
        if (!empty($missing)) {
            ResponseHandler::error('Campos requeridos faltantes: ' . implode(', ', $missing), 400);
        }
        
        $producto->nombre = $data['nombre'];
        $producto->descripcion = $data['descripcion'] ?? '';
        $producto->precio = $data['precio'];
        $producto->stock = $data['stock'];
        $producto->categoria_id = $data['categoria_id'] ?? null;
        $producto->imagen_url = $data['imagen_url'] ?? '';
        $producto->activo = true;
        
        if ($producto->create()) {
            $createdProduct = $producto->getById($producto->id);
            ResponseHandler::success($createdProduct, 'Producto creado exitosamente', 201);
        } else {
            ResponseHandler::error('Error al crear el producto', 500);
        }
        break;
        
    case 'PUT':
        ResponseHandler::validateMethod(['PUT']);
        $data = ResponseHandler::getRequestBody();
        
        if (!isset($data['id'])) {
            ResponseHandler::error('ID del producto requerido', 400);
        }
        
        $producto->id = intval($data['id']);
        $producto->nombre = $data['nombre'] ?? '';
        $producto->descripcion = $data['descripcion'] ?? '';
        $producto->precio = $data['precio'] ?? 0;
        $producto->stock = $data['stock'] ?? 0;
        $producto->categoria_id = $data['categoria_id'] ?? null;
        $producto->imagen_url = $data['imagen_url'] ?? '';
        $producto->activo = $data['activo'] ?? true;
        
        if ($producto->update()) {
            $updatedProduct = $producto->getById($producto->id);
            ResponseHandler::success($updatedProduct, 'Producto actualizado exitosamente');
        } else {
            ResponseHandler::error('Error al actualizar el producto', 500);
        }
        break;
        
    case 'DELETE':
        ResponseHandler::validateMethod(['DELETE']);
        
        if (!isset($query_params['id'])) {
            ResponseHandler::error('ID del producto requerido', 400);
        }
        
        $id = intval($query_params['id']);
        $producto->id = $id;
        
        if ($producto->delete()) {
            ResponseHandler::success(null, 'Producto eliminado exitosamente');
        } else {
            ResponseHandler::error('Error al eliminar el producto', 500);
        }
        break;
        
    default:
        ResponseHandler::error('Método no permitido', 405);
        break;
}
?>