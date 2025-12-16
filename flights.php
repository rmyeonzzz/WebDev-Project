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
    $_SESSION['login_error'] = 'Database connection error. Please try again later.';
    header("Location: login.html"); 
    exit();
}

$username = $_POST['username'] ?? '';
$password = $_POST['passwordd'] ?? '';
$redirect_url = $_POST['redirect_url'] ?? ''; 

if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = "Please enter both username and password.";
    header("Location: login.html");
    exit();
}

$sql_check = "SELECT USERID, completename, username FROM AGILA_REGISTRATION WHERE username = ? AND passwordd = ?";
$result_check = sqlsrv_query($conn, $sql_check, [$username, $password]);

if ($result_check && $row = sqlsrv_fetch_array($result_check, SQLSRV_FETCH_ASSOC)) {
    $_SESSION['loggedin'] = true; 
    $_SESSION['USERID'] = $row['USERID'];
    $_SESSION['completename'] = $row['completename'];
    $_SESSION['username'] = $row['username']; 
    
    if (!empty($redirect_url)) {
        header("Location: " . $redirect_url);
        exit();
    } else {
        header("Location: index.php"); 
        exit();
    }
} else {
    $_SESSION['login_error'] = "Invalid username or password.";
    $back_to_login_url = "login.html";
    if (!empty($redirect_url)) {
         $back_to_login_url .= "?redirect=" . urlencode($redirect_url);
    }
    header("Location: " . $back_to_login_url);
    exit();
}

sqlsrv_close($conn);
?>

