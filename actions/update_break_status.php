<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

include('../includes/connect.php');

session_start();
$login_id = $_SESSION['login_id'];

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request to break');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$start_time = date('Y-m-d H:i:s');
$break_end = date('Y-m-d H:i:s');
$break_time = $_POST['break_time'];


if ($action == 'start_break') {

    $insert_break = manage("INSERT INTO breaks (user_id, break_type, break_time, break_start) VALUES (?, ?, ?, ?)",
        array($login_id, 'short', 3600, $start_time)
    );

    if ($insert_break) {
        
        $update_status = manage("UPDATE users SET status = ? WHERE id = ?", array('on_break', $login_id));

        if ($update_status) {
            $response = array('status' => 'success', 'message' => 'Break started successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update user status.');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to insert break record.');
    }

} else if ($action == 'end_break') {

    $update_break = manage("UPDATE breaks SET break_end = ?, break_time = ? WHERE user_id = ? AND break_end IS NULL",
        array($break_end, $break_time, $login_id)
    );

    if ($update_break) {
        $update_status = manage("UPDATE users SET status = ? WHERE id = ?", array('active', $login_id));

        if ($update_status) {
            $response = array('status' => 'success', 'message' => 'Break ended successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update user status.');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update break record.');
    }
}

echo json_encode($response);

?>
