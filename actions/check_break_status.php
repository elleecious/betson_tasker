<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

include('../includes/connect.php');
include('../includes/session.php');

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'User not found or no break found.');


$break_result = retrieve("SELECT u.status, b.break_start 
    FROM users u LEFT JOIN breaks b ON u.id = b.user_id AND b.break_end IS NULL 
    WHERE u.id=?", array($login_id));

if ($break_result && count($break_result) > 0) {
    
    if ($break_result[0]['status'] === "on_break") {
        $breakStart = new DateTime($break_result[0]['break_start']);
        $now = new DateTime();
        $interval = $breakStart->diff($now);
        $secondsElapsed = $interval->s + ($interval->i * 60) + ($interval->h * 3600);
        $timeRemaining = max(0, 3600 - $secondsElapsed);

        $response = array(
            'status' => 'on_break',
            'time_remaining' => $timeRemaining
        );
    } else {
        $response = array(
            'status' => 'working',
        );
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'User not found or no break found.'
    );
}

echo json_encode($response);
?>
