<?php 
session_start();

$serverName = "LAPTOP-1AD6EHQ4"; 
$connectionOptions = [ 
    "Database" => "DLSU", 
    "Uid" => "", 
    "PWD" => "" 
]; 

$conn = sqlsrv_connect($serverName, $connectionOptions); 
if ($conn === false) {
    // In case of a database connection failure
    $_SESSION['login_error'] = 'Database connection error. Please try again later.';
    header("Location: login.html"); 
    exit();
}

// 1. Retrieve inputs from the form (POST method)
$username = $_POST['username'] ?? '';
$password = $_POST['passwordd'] ?? '';
// NEW: Retrieve the redirect URL passed from the hidden field in login.html
$redirect_url = $_POST['redirect_url'] ?? ''; 

if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = "Please enter both username and password.";
    header("Location: login.html");
    exit();
}

// 2. Query the database (using parameterized query for safety)
// Select the necessary user data: USERID (for session), completename, and username
$sql_check = "SELECT USERID, completename, username FROM AGILA_REGISTRATION WHERE username = ? AND passwordd = ?";
$result_check = sqlsrv_query($conn, $sql_check, [$username, $password]);

// 3. Process the result
if ($result_check && $row = sqlsrv_fetch_array($result_check, SQLSRV_FETCH_ASSOC)) {
    
    // Login SUCCESS: Store user data in the session
    $_SESSION['loggedin'] = true; 
    $_SESSION['USERID'] = $row['USERID'];
    $_SESSION['completename'] = $row['completename'];
    $_SESSION['username'] = $row['username']; 
    
    // Rule 1: Check for and apply conditional redirect
    if (!empty($redirect_url)) {
        // Redirect the user back to the specific page they were trying to access (booking.php with flight details)
        header("Location: " . $redirect_url);
        exit();
    } else {
        // Default redirect for a normal login
        header("Location: index.php"); 
        exit();
    }
    
} else {
    // Login FAILURE
    $_SESSION['login_error'] = "Invalid username or password.";
    
    // If login fails, redirect back to login.html.
    // Pass the redirect_url back in the query string so the user can try again later.
    $back_to_login_url = "login.html";
    if (!empty($redirect_url)) {
         $back_to_login_url .= "?redirect=" . urlencode($redirect_url);
    }
    header("Location: " . $back_to_login_url);
    exit();
}

sqlsrv_close($conn);
?>