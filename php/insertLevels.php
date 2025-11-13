<?php 
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer los datos JSON del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $descriptionPOST = $input['description'] ?? '';

    // validar que se haya proporcionado la descripcion
    if(empty($descriptionPOST)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "status" => 400,
            "message" => "La descripcion es requerida"
        ]);
        exit;
    }

    $url = "http://localhost:8220/insert_level/";
    $data = array("description" => $descriptionPOST);
    $jsonData = json_encode($data);

    // verificar token en cookie
    $token = $_COOKIE['token'] ?? null;
    if (!$token) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "status" => 401,
            "message" => "No se proporcionó token de autenticación"
        ]);
        exit;
    };

    // Configurar cURL
    $ch = curl_init($url);
    curl_setopt_array($ch,[
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            "Authorization: Bearer  $token"
        ],
        CURLOPT_POSTFIELDS => $jsonData
    ]);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  

    // Manejar errores de cURL
    if (curl_errno($ch)){
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => curl_error($ch)
        ]);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    // Decodificar respuesta del backend Python
    $json = json_decode($response, true);
    if ($json === null && json_last_error() != JSON_ERROR_NONE) {
        http_response_code(502);
        echo json_encode([
            "success" => false,
            "status" => 502,
            "message" => "Respuesta invalida del servidor remoto"
        ]);
        exit;
    }

    // Enviar resultado al frontend
    http_response_code(405);
    echo json_encode([
        "success" => true,
        "status" => $httpcode,
        "message" => $json
    ]);

} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "status" => 405,
        "message" => "Metodo no permitido. Usa POST"
    ]);
}

?>