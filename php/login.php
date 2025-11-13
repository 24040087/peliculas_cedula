<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Leer datos JSON enviados por el frontend
        $input = json_decode(file_get_contents('php://input'), true);
        $userPOST = $input['userlog'] ?? '';
        $passPOST = $input['passlog'] ?? '';

        // Validar campos requeridos
        if (empty($userPOST) || empty($passPOST)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "status" => 400,
                "message" => "Usuario y contraseña son requeridos"
            ]);
            exit;
        }

        $url = "https://localhost:7185/api/Auth/Token";
        // cambia las variables
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
        if ($json === null && json_last_error() != JSON_ERROR_NONE) {
            http_response_code(502);
            echo json_encode([
                "success" => false,
                "status" => 502,
                "message" => "Respuesta invalida del servidor remoto"
            ]);
            exit;
        }

        if ($httpcode === 200 && isset($json['token'])) {
            $token = $json['token'];
            $tokenParts = explode('.', $token);
            $payload = json_decode(base64_decode($tokenParts[1]), true);
            if (count($tokenParts) === 3) {
                $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1])), true);
            }

            $expTiome = isset($payload['exp']) ? ($payload['exp'] - time()) : 7200;
            if ($expTime <= 0) $expTime = 7200;

            setcookie("token", $token, time() + $expTime, "/", "", false, true);

            // Guardar datos en la sesión
            $_SESSION['username'] = strtolower($payload['sub'] ?? 'Sin Usuario');
            $_SESSION['fullname'] = strtolower($payload['fullname'] ?? 'Sin Nombre');
            $_SESSION['role'] = strtolower($payload['role'] ?? 'Sin Rol');

            http_response_code(200);
            echo json_encode([
                "success" => true,
                "status" => 200,
                "fullname" => $payload['fullname'] ?? null,
                "json" => $json
            ]);
            exit;
        }

        // si llega aqui, hubo error (usuario o contraseña)
        http_response_code($httpcode);
        echo json_encode([
            "success" => false,
            "status" => $httpcode,
            "json" => $json
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