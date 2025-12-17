<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

try {
    $param = isset($_GET['term']) ? $_GET['term'] : (isset($_GET['city']) ? $_GET['city'] : '');
    $city = trim($param);

    if (strlen($city) < 3) {
        echo json_encode([]);
        exit;
    }

    $clientId = "zOYLbxeukDPIjI7q8Ikl3bfEJFVahGbW"; 
    $clientSecret = "IFH8zBQlYmdlS8Sg"; 

    function callApi($url, $token = null, $postFields = null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        if ($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
        }
        if ($postFields) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        }
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400 || !$response) return null;
        return json_decode($response, true);
    }

    $authUrl = 'https://test.api.amadeus.com/v1/security/oauth2/token';
    $authData = "grant_type=client_credentials&client_id=$clientId&client_secret=$clientSecret";
    
    $tokenRes = callApi($authUrl, null, $authData);
    if (!isset($tokenRes['access_token'])) {
        throw new Exception("API Authentication Failed");
    }
    $token = $tokenRes['access_token'];

    $lat = '';
    $lon = '';
    
    $localDb = [
        'manila' => ['lat' => '14.5995', 'lon' => '120.9842'],
        'cebu' => ['lat' => '10.3157', 'lon' => '123.8854'],
        'boracay' => ['lat' => '11.9674', 'lon' => '121.9248'],
    ];
    $cityKey = strtolower($city);

    if (array_key_exists($cityKey, $localDb)) {
        $lat = $localDb[$cityKey]['lat'];
        $lon = $localDb[$cityKey]['lon'];
    } else {
        $geoUrl = "https://test.api.amadeus.com/v1/reference-data/locations?subType=CITY&keyword=" . urlencode($city) . "&page[limit]=1";
        $geoRes = callApi($geoUrl, $token);

        if (isset($geoRes['data'][0])) {
            $lat = $geoRes['data'][0]['geoCode']['latitude'];
            $lon = $geoRes['data'][0]['geoCode']['longitude'];
        } else {
            throw new Exception("Location not found.");
        }
    }

    $actUrl = "https://test.api.amadeus.com/v1/shopping/activities?latitude=$lat&longitude=$lon&radius=20&page[limit]=10";
    $actRes = callApi($actUrl, $token);

    $finalResults = [];

    if (isset($actRes['data']) && is_array($actRes['data'])) {
        foreach ($actRes['data'] as $act) {
            $img = 'pictures/logo2.png';
            if (isset($act['pictures'][0]['uri'])) $img = $act['pictures'][0]['uri'];

            $priceDisplay = 'Check Link';
            if (isset($act['price']['amount'])) {
                $priceDisplay = $act['price']['currencyCode'] . ' ' . $act['price']['amount'];
            }

            $finalResults[] = [
                'name' => $act['name'],
                'location' => ucfirst($city),
                'pictures' => [$img],
                'price' => ['amount' => $act['price']['amount'] ?? '', 'currencyCode' => $act['price']['currencyCode'] ?? ''],
                'bookingLink' => isset($act['bookingLink']) ? $act['bookingLink'] : '#'
            ];
        }
    }

    echo json_encode(['data' => $finalResults]);

} catch (Exception $e) {
    echo json_encode(['data' => [], 'error' => $e->getMessage()]);
}
?>