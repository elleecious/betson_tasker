<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

include('../includes/connect.php');

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request.');

$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];
$position = $_POST['position'];
$username = $_POST['username'];
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$created_at = date('Y-m-d H:i:s a');

$ip_address = getenv('HTTP_CLIENT_IP') ?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');


// Insert into the `users` table
$create_account_result = manage(
    "INSERT INTO users (lastname, firstname, position, username, user_password, created_at) 
    VALUES (?, ?, ?, ?, ?, ?)",
    array(
        $lastname,
        $firstname,
        $position,
        $username,
        $hashed_password,
        $created_at
    )
);

// Log the action in the `logs` table
$logs_result = manage(
    "INSERT INTO logs (computer_name, ip_address, page, action, details, date) 
    VALUES (?, ?, ?, ?, ?, ?)",
    array(
        gethostbyaddr($_SERVER['REMOTE_ADDR']),  // Get the host name from the IP
        $ip_address,                 // Get the IP address
        "Registration",                          // The page/action name
        "CREATE",                                // The action being logged
        "
            <details>
                <p>Create Account</p>
                <p>
                    Last Name: ".$lastname."<br>
                    First Name: ".$firstname."<br>
                    Position: ".$position."<br>
                    Username: ".$username."<br>
                    Date Created: ".$created_at."
                </p>
            </details>
        ", 
        date('Y-m-d H:i:s a')                     // Log the current timestamp
    )
);

// Set response based on result
if ($create_account_result && $logs_result) {
    $response = array('status' => 'success', 'message' => 'Registration successful');
} else {
    $response = array('status' => 'error', 'message' => 'Failed to register');
}

echo json_encode($response);
?>
