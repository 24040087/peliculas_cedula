<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $userPOST = $input['userlog'] ?? '';
        $passPOST = $input['passlog'] ?? '';

        if (empty($userPOST) || empty($passPOST)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "status" => 400,
                "message" => "Usuario y contraseña son requeridos"
            ]);
            exit;
        }

        $url = "http://localhost:8220/login";
        $data = array("username" => $userPOST, "password" => $passPOST);
        $jsonData = json_encode($data);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $jsonData
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => curl_error($ch)]);
            curl_close($ch);
            exit;
        }

        curl_close($ch);

        // decodificar la respuesta JSON
        $json = json_decode($response, true);
        http_response_code($httpcode);

        if ($httpcode === 200 && isset($json['access_token'])) {
            $token = $json['access_token'];
            $tokenParts = explode('.', $token);
            $payload = json_decode(base64_decode($tokenParts[1]), true);

            $expires = $payload['exp'];
            setcookie("token", $token, time() + $expires, "/", "", true, true);

            // Guardar datos en la sesión
            $_SESSION['username'] = strtolower($payload['sub'] ?? 'Sin Usuario');
            $_SESSION['fullname'] = strtolower($payload['fullname'] ?? 'Sin Nombre');
            $_SESSION['role'] = strtolower($payload['role'] ?? 'Sin Rol');
        }

        // Enviar al frontend
        header('Content-Type: application/json');
        echo json_encode([
            "success" => true,
            "status" => $httpcode,
            "fullname" => $payload['fullname'],
            "json" => $json
            // "response" => $json
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "status" => $httpcode,
            "message" => $json['message'] ?? "Error desconocido",
        ]);
    }
?>