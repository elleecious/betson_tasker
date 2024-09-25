<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

session_start();
include('../includes/connect.php');

header('Content-Type: application/json');

$login_id = $_SESSION['login_id'];
$breakt_type = "lunch";
$current_time = date('Y-m-d H:i:s');
$response = array('status' => 'error', 'message' => 'Failed to update lunch break');

$lunch_start_time = date('Y-m-d 01:00:00');
$lunch_end_time = date('Y-m-d 02:00:00');

if ($current_time >= $lunch_start_time && $current_time < $lunch_end_time) {
    
    $check_break = manage("SELECT * FROM breaks WHERE user_id = ? AND break_start = ? AND break_end IS NULL", array($login_id, $lunch_start_time));

    if ($check_break) {

        $update_break = manage("UPDATE breaks SET break_end = ? WHERE user_id = ? AND break_start = ? AND break_end IS NULL", array($lunch_end_time, $login_id, $lunch_start_time));

        if ($update_break) {
            $response = array('status' => 'success', 'message' => 'Lunch break ended successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to end lunch break');
        }
    } else {

        $insert_break = manage("INSERT INTO breaks (user_id, break_start, break_end, break_type) VALUES (?, ?, ?, ?)", array($login_id, $lunch_start_time,'', $breakt_type));
        
        if ($insert_break) {

            $update_status = manage("UPDATE users SET status = ? WHERE id = ?", array('on_break', $login_id));
            
            if ($update_status) {
                $response = array('status' => 'success', 'message' => 'Lunch break started successfully.');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to start lunch break');
            }
        }
    }
}

echo json_encode($response);
?>
