<?php

session_start();  // Start the session

$timeout_duration = 1800;
// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit;
}

if(isset($_SESSION['login_time'])) {
    if ((time() - $_SESSION['login_time']) > $timeout_duration) {
        // If session has expired, destroy the session and redirect to login
        session_unset();
        session_destroy();
        header("Location: login.html"); //would be nice to alert the customer that their session has expired
        exit;
    } else {
        // Update the login time to extend the session
        $_SESSION['login_time'] = time();
    }
}

// Step 1: Exchange authorization code for access token
// if we need to change scope, then would need to get a new authorization code and new refresh token


$fields = [
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'refresh_token' => $refresh_token,
    'grant_type' => 'refresh_token'
];

$ch = curl_init($auth_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

$response = curl_exec($ch);
if (!$response) {
    die("Error fetching access token: " . curl_error($ch));
}

$tokens = json_decode($response, true);
$access_token = $tokens['access_token'];
curl_close($ch);


// Step 2: Get the form data sent to the script... Use access token to create a new activity
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $distance = $_POST['form_distance']; //will need to add some calculations here because this needs to be in meters
    $time = $_POST['form_time']; //will neeed to add some calculations here because this needs to be in seconds
    $start_time = $_POST['form_start_time']; //will need to make sure this is formatted correctly

    $distance_meters = $distance * 1609.34;
    $time_seconds = $time * 60;
    $time_format = $start_time + "T17";

    $activities_url = "https://www.strava.com/api/v3/athlete/activities";

    $actual_file = realpath($filename);

    $url="https://www.strava.com/api/v3/uploads";


    $postfields = array(
        "name" => "Treadmill Run",
        "type" => "Run", 
        "sport_type" => "Run", 
        "start_time_local" => $time_format, //needs to be a certain format... 
        "elapssed_time" => $time_seconds, //needs to be in seconds
        "description" => "treamill run uploaded from Sam's treadmill uploader app", 
        "distance" => strval($distance_meters),//needs to be in meters
        "trainer" => 0,
        "commute" => 0
    );

    $headers = array('Authorization: Bearer ' . $access_token);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $activities_url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    $response = curl_exec($ch);
    if (!$response) {
        die("Error fetching activities: " . curl_error($ch));
    }
}
?>