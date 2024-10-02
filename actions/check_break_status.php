<?php
include('../includes/connect.php');
session_start();

$login_id = $_SESSION['login_id'];

header('Content-Type: application/json');

$response = array('status' => 'active', 'message' => 'User is not on break.');

$break_data = retrieve("SELECT break_start, break_time FROM breaks WHERE user_id = ? AND break_end IS NULL", array($login_id));

if (!empty($break_data)) {
    $break_start = strtotime($break_data[0]['break_start']);
    $break_time = (int)$break_data[0]['break_time']; 
    $current_time = time();
    $elapsed_time = $current_time - $break_start;

    if ($elapsed_time < $break_time) {
        $time_remaining = $break_time - $elapsed_time;
        $response = array('status' => 'on_break', 'time_remaining' => $time_remaining);
    } else {
        $response = array('status' => 'active', 'message' => 'Break time is over but not marked as ended.');
    }
}

echo json_encode($response);
?>
