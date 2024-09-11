<?php
session_start();
$_SESSION = array();

// If using cookies for sessions, delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

// Destroy the session itself
session_destroy();

// Return a success response
echo json_encode(array('status' => 'success', 'message' => 'Logged out successfully'));
?>
