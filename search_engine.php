<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

try {
    if (!function_exists('curl_init')) {
        throw new Exception("cURL is NOT enabled on this server.");
    }

    $query = isset($_GET['term']) ? trim($_GET['term']) : '';

    if (strlen($query) < 3) {
        echo json_encode([]);
        exit;
    }

    $apiKey = "zOYLbxeukDPIjI7q8Ikl3bfEJFVahGbW";
    $apiSecret = "IFH8zBQlYmdlS8Sg";
    $authUrl = "https://test.api.amadeus.com/v1/security/oauth2/token";

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
        
        if (curl_errno($ch)) {
            throw new Exception('CURL Connection Error: ' . curl_error($ch));
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            $json = json_decode($response, true);
            $msg = isset($json['errors'][0]['title']) ? $json['errors'][0]['title'] : $response;
            throw new Exception("API Error ($httpCode): " . $msg);
        }

        return json_decode($response, true);
    }

    $authRes = callApi($authUrl, null, "grant_type=client_credentials&client_id=$apiKey&client_secret=$apiSecret");

    if (!isset($authRes['access_token'])) {
        throw new Exception('Authentication Failed. Check API Keys.');
    }
    $token = $authRes['access_token'];

    $cityUrl = "https://test.api.amadeus.com/v1/reference-data/locations?subType=CITY,AIRPORT&keyword=" . urlencode($query) . "&page[limit]=1";
    $cityData = callApi($cityUrl, $token);

    if (!isset($cityData['data'][0])) {
        echo json_encode([]); 
        exit;
    }

    $location = $cityData['data'][0];
    $lat = $location['geoCode']['latitude'];
    $long = $location['geoCode']['longitude'];
    
    $locationName = isset($location['address']['cityName']) 
        ? $location['address']['cityName'] 
        : (isset($location['name']) ? $location['name'] : $query);

    $activitiesUrl = "https://test.api.amadeus.com/v1/shopping/activities?latitude=$lat&longitude=$long&radius=15&page[limit]=5";
    $activitiesData = callApi($activitiesUrl, $token);

    $results = [];

    if (isset($activitiesData['data']) && count($activitiesData['data']) > 0) {
        foreach ($activitiesData['data'] as $act) {
            $imageLink = 'pictures/logo2.png'; 
            if (isset($act['pictures'][0]['uri'])) {
                $imageLink = $act['pictures'][0]['uri'];
            } elseif (isset($act['pictures'][0]['file'])) {
                $imageLink = $act['pictures'][0]['file'];
            } elseif (isset($act['pictures'][0]) && is_string($act['pictures'][0])) {
                $imageLink = $act['pictures'][0];
            }

            $results[] = [
                'name' => $act['name'],
                'location' => $locationName,
                'image' => $imageLink,
                'link' => isset($act['bookingLink']) ? $act['bookingLink'] : '#'
            ];
        }
    } elseif (stripos($locationName, 'Manila') !== false) {
        $results[] = [
            'name' => 'Intramuros Historic Tour',
            'location' => 'Manila, Philippines',
            'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/Fort_Santiago_Gate.jpg/800px-Fort_Santiago_Gate.jpg',
            'link' => 'https://guidetothephilippines.ph/destinations-and-attractions/intramuros',
        ];
        $results[] = [
            'name' => 'Rizal Park (Luneta)',
            'location' => 'Ermita, Manila',
            'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/Rizal_Monument_during_sunset.jpg/800px-Rizal_Monument_during_sunset.jpg',
            'link' => 'https://nationalparks.ph/',
        ];
        $results[] = [
            'name' => 'Binondo Food Crawl',
            'location' => 'Chinatown, Manila',
            'image' => 'https://images.unsplash.com/photo-1550966871-3ed3c47e2ce2?q=80&w=1000&auto=format&fit=crop',
            'link' => '#',
        ];
    } else {
        $results[] = [
            'name' => "Explore " . $locationName,
            'location' => $locationName,
            'image' => 'pictures/logo2.png',
            'link' => '#',
            'description' => 'No specific activities found (API Test Limit).'
        ];
    }

    echo json_encode($results);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>