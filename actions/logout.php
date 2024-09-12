<?php
include('../includes/connect.php');
session_start();

if (isset($_SESSION['login_username'])) {
    $login_username = $_SESSION['login_username'];
} else {
    $login_username = "Unknown";
}

$_SESSION = array();

$ip_address = getenv('HTTP_CLIENT_IP') ?: getenv('HTTP_X_FORWARDED_FOR') ?: getenv('HTTP_X_FORWARDED') ?: getenv('HTTP_FORWARDED_FOR') ?: getenv('HTTP_FORWARDED') ?: getenv('REMOTE_ADDR');
$ip_address_2 = ($_SERVER['REMOTE_ADDR'] == '::1') ? '127.0.0.1' : $ip_address;

if (ini_get("session.use_cookies")) {
    manage("INSERT INTO logs (computer_name, ip_address, page, action, details, date)
        VALUES (?, ?, ?, ?, ?, ?)", 
    array(
        gethostbyaddr($_SERVER['REMOTE_ADDR']),
        $ip_address_2,
        "NONE",
        "LOGOUT",
        "<details>
            <p>User Logout</p>
            <p>Username: " . $login_username . "</p>
        </details>",
        date("Y-m-d H:i:s a")
    ));

    // Clear the session cookie
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

session_destroy();

echo json_encode(array('status' => 'success', 'message' => 'Logged out successfully'));
?>
