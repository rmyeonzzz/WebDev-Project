<?php

function fetchAttractions($query) {

    $rapidApiKey = '76d543e97fmshe6d7b4b9e6300d0p18dbc4jsn179b022e8a2f';
    $url = 'https://booking-com15.p.rapidapi.com/api/v1/attraction/searchLocation?query=' . urlencode($query) . '&languagecode=en-us';

    $ch = curl_init();
    

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-rapidapi-host: booking-com15.p.rapidapi.com',
        "x-rapidapi-key: $rapidApiKey"
    ]);


    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);


    if ($httpCode !== 200 || $response === false) {
        return ['status' => false, 'message' => "API Request Failed with status code: $httpCode"];
    }


    return json_decode($response, true);
}


$attractions_data = fetchAttractions('Philippines');


?>
