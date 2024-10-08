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
$auth_url = "https://www.strava.com/oauth/token";
$client_id = '135336';
$client_secret = '50fd40b61efec068ccfb5a5ab74a827f43fdf12b';
$refresh_token = '15c127d4c7f38f3a412c4f9700f3cc9b5484165a'; // From the redirect after user authorization

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

//echo "Access Token: " . $access_token . "\n";

// Step 2: Use access token to fetch athlete activities
$activities_url = "https://www.strava.com/api/v3/athlete/activities";

$ch = curl_init($activities_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token  // Authorization header with Bearer token
]);

$response = curl_exec($ch);
if (!$response) {
    die("Error fetching activities: " . curl_error($ch));
}

$activities = json_decode($response, true);
curl_close($ch);

// Step 3: Print the activities
//foreach ($activities as $activity) {
//    echo "Name: " . $activity['name'] . ", Distance: " . $activity['distance'] . " meters\n";
//}
// If the user is logged in, display the content of the page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
    <p>This is a protected page only accessible to logged-in users.</p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Distance (meters)</th>
                <th>Moving Time (seconds)</th>
                <th>Average Speed (m/s)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Step 5: Loop through activities and populate table rows
            if (!empty($activities)) {
                foreach ($activities as $activity) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($activity['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($activity['distance']) . "</td>";
                    echo "<td>" . htmlspecialchars($activity['moving_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($activity['average_speed']) . "</td>";
                    echo "<td>" . htmlspecialchars(date('Y-m-d', strtotime($activity['start_date']))) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No activities found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <form action="API/stravaSubmit.php" method="POST">
        <label for="form_date">Date (yyyy-mm-dd):</label>
        <input type="text" id="form_date" name="form_date" required><br><br>

        <label for="form_distance">Distance (in miles):</label>
        <input type="float" id="form_distance" name="form_distance" required><br><br>

        <label for="form_time">Time (in minutes):</label>
        <input type="text" id="form_time" name="form_time" required><br><br>

        <button type="submit">Submit</button>
    </form>

        <!-- Display Success or Error Message -->
        <?php if (isset($_GET['status'])): ?>
        <div id="statusMessage">
            <?php
                if ($_GET['status'] == 'success') {
                    echo "<p style='color: green;'>New activity created successfully!</p>";
                } elseif ($_GET['status'] == 'error') {
                    echo "<p style='color: red;'>There was an error creating the activity.</p>";
                }
            ?>
        </div>
    <?php endif; ?>
</body>
</html>