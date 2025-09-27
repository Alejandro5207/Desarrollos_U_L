<?php
class ResponseHandler {
    
    public static function success($data = null, $message = 'Operación exitosa', $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public static function error($message = 'Error en la operación', $code = 400, $details = null) {
        http_response_code($code);
        header('Content-Type: application/json');
        
        $response = [
            'success' => false,
            'message' => $message,
            'error_code' => $code,
            'details' => $details,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public static function validateMethod($allowedMethods) {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if (!in_array($method, $allowedMethods)) {
            self::error('Método no permitido', 405, 'Método ' . $method . ' no está permitido');
        }
    }
    
    public static function getRequestBody() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            self::error('JSON inválido', 400, 'Error al decodificar JSON: ' . json_last_error_msg());
        }
        
        return $data;
    }
    
    public static function validateRequired($data, $requiredFields) {
        $missing = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            self::error('Campos requeridos faltantes', 400, 'Campos faltantes: ' . implode(', ', $missing));
        }
    }
}
?>
