<?php
$api_url = 'http://localhost/AA1_Gonzalez_Velandia_Mauricio_Alejandro/api/productos.php';

function makeRequest($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'data' => json_decode($response, true),
        'http_code' => $http_code
    ];
}

echo "<h1>Pruebas de API REST - Productos</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .test { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    .success { background-color: #d4edda; border-color: #c3e6cb; }
    .error { background-color: #f8d7da; border-color: #f5c6cb; }
    .response { background-color: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 3px; }
    pre { white-space: pre-wrap; word-wrap: break-word; }
</style>";

$test_results = [];

$test1 = makeRequest($api_url);
$test_results[] = [
    'name' => 'GET - Obtener todos los productos',
    'success' => $test1['http_code'] == 200,
    'response' => $test1
];

$test2 = makeRequest($api_url . '?id=1');
$test_results[] = [
    'name' => 'GET - Obtener producto por ID',
    'success' => $test2['http_code'] == 200,
    'response' => $test2
];

$test3_data = [
    'nombre' => 'Producto de Prueba',
    'descripcion' => 'Descripción del producto de prueba',
    'precio' => 99999,
    'stock' => 10,
    'categoria_id' => 1,
    'imagen_url' => 'https://example.com/test.jpg'
];

$test3 = makeRequest($api_url, 'POST', $test3_data);
$test_results[] = [
    'name' => 'POST - Crear producto',
    'success' => $test3['http_code'] == 201,
    'response' => $test3
];

$created_id = null;
if ($test3['data'] && isset($test3['data']['data']['id'])) {
    $created_id = $test3['data']['data']['id'];
}

if ($created_id) {
    $test4_data = [
        'id' => $created_id,
        'nombre' => 'Producto Actualizado',
        'descripcion' => 'Descripción actualizada',
        'precio' => 149999,
        'stock' => 5,
        'categoria_id' => 2,
        'imagen_url' => 'https://example.com/updated.jpg'
    ];
    
    $test4 = makeRequest($api_url, 'PUT', $test4_data);
    $test_results[] = [
        'name' => 'PUT - Actualizar producto',
        'success' => $test4['http_code'] == 200,
        'response' => $test4
    ];
    
    $test5 = makeRequest($api_url . '?id=' . $created_id, 'DELETE');
    $test_results[] = [
        'name' => 'DELETE - Eliminar producto',
        'success' => $test5['http_code'] == 200,
        'response' => $test5
    ];
}

$test6 = makeRequest($api_url . '?search=Samsung');
$test_results[] = [
    'name' => 'GET - Buscar productos',
    'success' => $test6['http_code'] == 200,
    'response' => $test6
];

foreach ($test_results as $test) {
    $class = $test['success'] ? 'success' : 'error';
    echo "<div class='test $class'>";
    echo "<h3>" . $test['name'] . "</h3>";
    echo "<p><strong>Estado:</strong> " . ($test['success'] ? 'PASÓ' : 'FALLÓ') . "</p>";
    echo "<p><strong>Código HTTP:</strong> " . $test['response']['http_code'] . "</p>";
    echo "<div class='response'>";
    echo "<strong>Respuesta:</strong>";
    echo "<pre>" . json_encode($test['response']['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    echo "</div>";
    echo "</div>";
}

$passed = array_filter($test_results, function($test) { return $test['success']; });
$total = count($test_results);
$passed_count = count($passed);

echo "<div class='test'>";
echo "<h2>Resumen de Pruebas</h2>";
echo "<p><strong>Pruebas pasadas:</strong> $passed_count / $total</p>";
echo "<p><strong>Porcentaje de éxito:</strong> " . round(($passed_count / $total) * 100, 2) . "%</p>";
echo "</div>";
?>