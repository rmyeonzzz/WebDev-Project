<?php

function fetchAttractions($query) {
    // ⚠️ IMPORTANT: Replace 'YOUR_RAPIDAPI_KEY' with your actual key, 
    // but consider using a more secure method like environment variables (env file) 
    // instead of hardcoding it.
    $rapidApiKey = '76d543e97fmshe6d7b4b9e6300d0p18dbc4jsn179b022e8a2f';
    $url = 'https://booking-com15.p.rapidapi.com/api/v1/attraction/searchLocation?query=' . urlencode($query) . '&languagecode=en-us';

    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-rapidapi-host: booking-com15.p.rapidapi.com',
        "x-rapidapi-key: $rapidApiKey"
    ]);

    // Execute the request and close the connection
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check for cURL or HTTP errors
    if ($httpCode !== 200 || $response === false) {
        // Log the error or return an empty array on failure
        return ['status' => false, 'message' => "API Request Failed with status code: $httpCode"];
    }

    // Decode the JSON response into a PHP associative array
    return json_decode($response, true);
}

// Example usage:
$attractions_data = fetchAttractions('Philippines');
// var_dump($attractions_data); // Use for testing the raw output

?>
