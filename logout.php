<?php
session_start();

// 1. Unset all session variables.
// This is important for removing the data (like USERID, completename)
// while keeping the session active for the next step.
$_SESSION = array();

// 2. Destroy the session.
// This deletes the session file/cookie on the server.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// 3. Redirect the user back to the home page or login page
header("Location: index.html");
exit;
?>