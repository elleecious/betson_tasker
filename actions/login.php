<?php

include('../includes/connect.php');
include('../library/functions.php');
header('Content-Type: application/json');

$response = array('status' => 'error', 'message' => 'An error occured.');

$username = $_POST['username'];
$password = $_POST['password'];


$user = retrieve("SELECT * FROM users WHERE username = ?", array($username));

if ($user) {
    $user = $user[0];
    if (password_verify($password, $user['user_password'])) {
        session_start();
        $_SESSION['login_id'] = $user['id'];
        manage("INSERT INTO logs (computer_name,ip_address,page,action,details,date)
                VALUES (?,?,?,?,?,?)
            ",array(
                gethostbyaddr($_SERVER['REMOTE_ADDR']),
                getPublicIP(),
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
