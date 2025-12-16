<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

$apiKey = "nry6rhtrc3BzW7AklTuAVXvO3hHWrCc1";
$apiSecret = "Z4vvo9EYn9AOzTzm";
$authUrl = "https://test.api.amadeus.com/v1/security/oauth2/token";

function callApi($url, $postFields = null, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    
    if ($postFields) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        return null; 
    }
    
    curl_close($ch);
    return $response;
}

$authResponse = callApi($authUrl, http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $apiKey,
    'client_secret' => $apiSecret
]));

$tokenData = json_decode($authResponse, true);

if (!isset($tokenData['access_token'])) {
    echo json_encode(['error' => 'Failed to authenticate']);
    exit;
}

$accessToken = $tokenData['access_token'];

$destinations = [
    ["name" => "Manila",    "lat" => 14.5995, "long" => 120.9842],
    ["name" => "Cebu",      "lat" => 10.3157, "long" => 123.8854],
    ["name" => "Tokyo",     "lat" => 35.6762, "long" => 139.6503],
    ["name" => "Singapore", "lat" => 1.3521,  "long" => 103.8198],
    ["name" => "Bangkok",   "lat" => 13.7563, "long" => 100.5018],
    ["name" => "Dubai",     "lat" => 25.276987, "long" => 55.296249],
    ["name" => "Paris",     "lat" => 48.8566, "long" => 2.3522],
    ["name" => "London",    "lat" => 51.5074, "long" => -0.1278],
    ["name" => "New York",  "lat" => 40.7128, "long" => -74.0060]
];

$diverseActivities = [];

foreach ($destinations as $place) {
    $lat = $place['lat'];
    $long = $place['long'];
    $radius = 15;

    $url = "https://test.api.amadeus.com/v1/shopping/activities?latitude=$lat&longitude=$long&radius=$radius&page[limit]=1";
    
    $headers = ["Authorization: Bearer $accessToken"];
    $response = callApi($url, null, $headers);
    
    if ($response) {
        $json = json_decode($response, true);
        if (isset($json['data']) && count($json['data']) > 0) {
            $diverseActivities[] = $json['data'][0];
        }
    }
}

echo json_encode(['data' => $diverseActivities]);
?>