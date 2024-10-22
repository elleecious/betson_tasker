<?php

ini_set('log_errors', 1);
    ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
    error_reporting(E_ALL);

    include('../includes/connect.php');
    session_start();
    header('Content-Type: application/json');
    
    $response = array('status' => 'error', 'message' => 'Invalid request reset break time.');
    $login_id = $_SESSION['login_id'];

    $currentDateTime = new DateTime();
    $shiftStart = clone $currentDateTime;
    $shiftStart->setTime(21, 0, 0);
    $shiftEnd = clone $shiftStart;
    $shiftEnd->modify('+9 hours');

    if ($currentDateTime < $shiftStart) {
        $shiftStart->modify('-1 day');
        $shiftEnd->modify('-1 day');
    }

    $last_shift_query = retrieve(
        "SELECT last_break_date FROM breaks WHERE user_id = ? ORDER BY break_start DESC LIMIT 1",
        array($login_id)
    );

    if (!empty($last_shift_query)) {
        $last_break_date = new DateTime($last_shift_query[0]['last_break_date']);
    } else {
        $last_break_date = null;
    }

    $isNewShift = ($last_break_date < $shiftStart);

    if ($isNewShift) {
        
        $reset_query = manage("UPDATE users SET total_break_time = 0 WHERE id = ?", array($login_id));
        if ($reset_query) {
            $response = array('status' => 'success', 'message' => 'Break time reset for the new shift.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to reset break time.');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Break time is still valid for this shift.');
    }

    echo json_encode($response);

?>