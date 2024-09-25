<?php

    ini_set('log_errors', 1);
    ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
    error_reporting(E_ALL);

    include('../includes/connect.php');

    session_start();
    $login_id = $_SESSION['login_id'];

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'User not found or no break found.');


    $break_info = retrieve("SELECT break_time FROM breaks WHERE user_id=? AND break_end IS NULL",array($login_id));

    if ($break_info) {
        $response = array(
            'status' => 'on_break',
            'break_time' => $break_info[0]['break_time'];
        );
    } else {
        $response = array(
            'status' => 'working',
            'message' => 'Working Mode'
        );
    }
    
    echo json_encode($response);

?>
