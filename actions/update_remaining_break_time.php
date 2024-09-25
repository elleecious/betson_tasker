<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

include('../includes/connect.php');
session_start();
$login_id = $_SESSION['login_id'];

header('Content-Type: application/json');

$response = array('status' => 'error', 'message' => 'Invalid request');

$end_time = date('Y-m-d H:i:s');

$update_break = manage("UPDATE breaks SET break_end = ? WHERE user_id = ? AND break_end IS NULL", array($end_time, $login_id));

if ($update_break) {
    $update_status = manage("UPDATE users SET status = ? WHERE id = ?", array("active",$login_id));
    
    if ($update_status) {
        $response = array('status' => 'success', 'message' => 'Break ended successfully.');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update user status.');
    }

}  else {
    $response = array('status' => 'error', 'message' => 'Failed to update break end time.');
}

echo json_encode($response);
?>
