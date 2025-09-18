<?php
function enviarWhatsapp($telefono, $mensaje){
    $data = [
        'phone' => $telefono, // en formato internacional 57XXXXXXXXXX
        'body'  => $mensaje
    ];
    $json = json_encode($data);

    $token = '83763g87x';   // 🔑 tu token
    $instanceId = '777';    // 🔑 tu instancia
    $url = "https://api.chat-api.com/instance{$instanceId}/sendMessage?token={$token}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}