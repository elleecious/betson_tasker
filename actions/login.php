<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);


include('../includes/connect.php');
header('Content-Type: application/json');

$response = array('status' => 'error', 'message' => 'Invalid request.');

$username = $_POST['username'];
$password = $_POST['password'];

// Retrieve user from the database
$user = retrieve("SELECT * FROM users WHERE username = ?", array($username));

if ($user) {
    $user = $user[0];
    if (password_verify($password, $user['user_password'])) {
        session_start();
        $_SESSION['login_username'] = $user['username'];

        $response = array('status' => 'success', 'message' => 'Login successful.');
    } else {
        $response['message'] = 'Incorrect password.';
    }
} else {
    $response['message'] = 'User not found.';
}

echo json_encode($response);
?>
