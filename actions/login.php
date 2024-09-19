<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);


include('../includes/connect.php');
header('Content-Type: application/json');

$ip_address = getenv('HTTP_CLIENT_IP') ?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');

$ip_address_2 = ($_SERVER['REMOTE_ADDR'] == '::1') ? '127.0.0.1' : $ip_address;

$response = array('status' => 'error', 'message' => 'An error occured.');

$username = $_POST['username'];
$password = $_POST['password'];

// Retrieve user from the database
$user = retrieve("SELECT * FROM users WHERE username = ?", array($username));

if ($user) {
    $user = $user[0];
    if (password_verify($password, $user['user_password'])) {
        session_start();
        $_SESSION['login_id'] = $user['id'];
        error_log('User logged in with ID: ' . $_SESSION['login_id']); //Debugging line
        manage("INSERT INTO logs (computer_name,ip_address,page,action,details,date)
                VALUES (?,?,?,?,?,?)
            ",array(
                gethostbyaddr($_SERVER['REMOTE_ADDR']),
                $ip_address_2,
                "Login",
                "LOGIN",
                "<details>
                    <p>User Login</p>
                    <p>Username: ".$username."</p>
                </details>",
                date("Y-m-d H:i:s a")
            )
        );
        $response = array('status' => 'success', 'message' => 'Login successful.');
    } else {
        $response['message'] = 'Incorrect password.';
    }
} else {
    $response['message'] = 'User not found.';
}

echo json_encode($response);
?>
