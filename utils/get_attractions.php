<?php
// utils/get_attractions.php
header('Content-Type: application/json');

// 1. Get the city from the frontend
$city = isset($_GET['city']) ? strtolower(trim($_GET['city'])) : '';

if (empty($city)) {
    echo json_encode(["error" => "City name is required"]);
    exit;
}

// 2. CONVERT CITY TO COORDINATES
// Amadeus needs Lat/Lon. This is a mini-database for your project.
// You can add more cities to this list!
$cityCoordinates = [
    // LUZON
    'manila' => ['lat' => '14.5995', 'lon' => '120.9842'],
    'baguio' => ['lat' => '16.4023', 'lon' => '120.5960'],
    'tagaytay' => ['lat' => '14.1153', 'lon' => '120.9621'],
    'vigan' => ['lat' => '17.5709', 'lon' => '120.3872'],
    'laoag' => ['lat' => '18.1960', 'lon' => '120.5927'],
    'sagada' => ['lat' => '17.0822', 'lon' => '120.9000'],
    'legazpi' => ['lat' => '13.1391', 'lon' => '123.7438'], // Mayon Volcano
    'batangas' => ['lat' => '13.7565', 'lon' => '121.0583'],
    'puerto galera' => ['lat' => '13.5036', 'lon' => '120.9576'],
    'subic' => ['lat' => '14.8214', 'lon' => '120.2867'],
    'baler' => ['lat' => '15.7584', 'lon' => '121.5606'],

    // VISAYAS
    'cebu' => ['lat' => '10.3157', 'lon' => '123.8854'],
    'bohol' => ['lat' => '9.8500', 'lon' => '124.1435'], // Chocolate Hills
    'panglao' => ['lat' => '9.5936', 'lon' => '123.8050'],
    'boracay' => ['lat' => '11.9674', 'lon' => '121.9248'],
    'iloilo' => ['lat' => '10.7202', 'lon' => '122.5621'],
    'bacolod' => ['lat' => '10.6713', 'lon' => '122.9510'],
    'dumaguete' => ['lat' => '9.3068', 'lon' => '123.3054'],
    'tacloban' => ['lat' => '11.2443', 'lon' => '125.0030'],

    // MINDANAO
    'davao' => ['lat' => '7.1907', 'lon' => '125.4553'],
    'siargao' => ['lat' => '9.8702', 'lon' => '126.0468'], // Cloud 9
    'cagayan de oro' => ['lat' => '8.4542', 'lon' => '124.6319'],
    'camiguin' => ['lat' => '9.1732', 'lon' => '124.7299'],
    'zamboanga' => ['lat' => '6.9214', 'lon' => '122.0790'],
    'general santos' => ['lat' => '6.1164', 'lon' => '125.1716'],

    // PALAWAN SPECIALS
    'palawan' => ['lat' => '9.8349', 'lon' => '118.7384'],
    'puerto princesa' => ['lat' => '9.7392', 'lon' => '118.7350'],
    'el nido' => ['lat' => '11.1956', 'lon' => '119.4180'],
    'coron' => ['lat' => '11.9968', 'lon' => '120.2039']
];

if (!array_key_exists($city, $cityCoordinates)) {
    echo json_encode([
        "error" => "Location not found in database. Try: Manila, Cebu, Bohol, Palawan, Baguio, or Boracay."
    ]);
    exit;
}

$lat = $cityCoordinates[$city]['lat'];
$lon = $cityCoordinates[$city]['lon'];

// 3. YOUR AMADEUS CREDENTIALS
$clientId = 'MjmwtiZNMq5qpVjs2Ad9vwJFPXW9oAGf'; 
$clientSecret = 'Xulqcr2yBlXQbJ9z'; 

// 4. GET ACCESS TOKEN (Login to Amadeus)
$url = 'https://test.api.amadeus.com/v1/security/oauth2/token';
$data = 'grant_type=client_credentials&client_id=' . $clientId . '&client_secret=' . $clientSecret;

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => 0, // Disable SSL for localhost (Fixes cURL Error)
    CURLOPT_SSL_VERIFYPEER => 0
]);

$response = curl_exec($curl);
$tokenData = json_decode($response, true);

if (!isset($tokenData['access_token'])) {
    // If login fails, send error back to frontend
    echo json_encode(["error" => "API Authentication Failed. Check keys."]);
    exit;
}

$accessToken = $tokenData['access_token'];

// 5. SEARCH FOR TOURS & ACTIVITIES
// Radius is 20km around the city center
$searchUrl = "https://test.api.amadeus.com/v1/shopping/activities?latitude=$lat&longitude=$lon&radius=20";

curl_setopt_array($curl, [
    CURLOPT_URL => $searchUrl,
    CURLOPT_POST => false, // GET request
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $accessToken
    ]
]);

$result = curl_exec($curl);
curl_close($curl);

// 6. Return the raw data
echo $result;
?>