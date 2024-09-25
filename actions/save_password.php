<?php
    ini_set('log_errors', 1);
    ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
    error_reporting(E_ALL);

    include('../includes/connect.php');
    include('../includes/session.php');

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'User not found or no task found.');

    $ip_address = getenv('HTTP_CLIENT_IP') ?: getenv('HTTP_X_FORWARDED_FOR')?:
    getenv('HTTP_X_FORWARDED')?: getenv('HTTP_FORWARDED_FOR')?:
    getenv('HTTP_FORWARDED')?: getenv('REMOTE_ADDR');
    $ip_address_2 = ($_SERVER['REMOTE_ADDR'] == '::1') ? '127.0.0.1' : $ip_address;

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    $hashed_password = retrieve("SELECT user_password FROM users WHERE id = ?", array($login_id));

    if ($hashed_password && password_verify($current_password, $hashed_password[0]['user_password'])) {

        $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $update = manage("UPDATE users SET user_password = ? WHERE id = ?", array($new_hashed_password, $login_id));
        $logs_result = manage(
            "INSERT INTO logs (computer_name,ip_address,page,action,details,date) 
            VALUES (?,?,?,?,?,?)",
            array(
                gethostbyaddr($_SERVER['REMOTE_ADDR']),
                $ip_address_2,              
                "CHANGE PASSWORD",
                "CHANGE",
                "<details>
                    <p>CHANGE PASSWORD</p>
                        <p>Name: ".$name."</p>
                        <p>Date: ".date('Y-m-d H:i:s a')."</p>
                    </details>
                ", 
                date('Y-m-d H:i:s a')));

        if ($update && $logs_result) {
            $response = array('status' => 'success', 'message' => 'Password changed successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to change password.');
        }
    } else {
            $response = array('status' => 'error', 'message' => 'Current password is incorrect.');
    }

    echo json_encode($response);
?>
