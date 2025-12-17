<?php
function getAmadeusToken() {

    $apiKey = 'zOYLbxeukDPIjI7q8Ikl3bfEJFVahGbW';
    $apiSecret = 'IFH8zBQlYmdlS8Sg'; 
    
    $url = 'https://test.api.amadeus.com/v1/security/oauth2/token';

    $data = http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => $apiKey,
        'client_secret' => $apiSecret
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        die('Connection Error: ' . curl_error($ch));
    }
    curl_close($ch);

    $json = json_decode($response, true);
    
    if (isset($json['access_token'])) {
        return $json['access_token'];
    } else {
        die('Auth Failed: ' . print_r($json, true));
    }
}
?>