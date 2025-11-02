<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $userPOST = $input['userlog'] ?? '';
        $passPOST = $input['passlog'] ?? '';

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
            setcookie("token", $token, time() + 300, "/", "", true, true);

            $_SESSION['username'] = $userPOST;
        }

        // Enviar al frontend
        header('Content-Type: application/json');
        echo json_encode([
            "success" => true,
            "status" => $httpcode
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "status" => $httpcode,
            "message" => $json['message'] ?? "Error desconocido"
        ]);
    }
?>