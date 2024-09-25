<?php
    include('../includes/connect.php');

    session_start();
    $login_id = $_SESSION['login_id'];

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'Failed to retrieve remaining break time.');

    $total_break_time = 3600;

    $break_data = retrieve("SELECT user_id, break_start FROM breaks WHERE user_id = ? AND break_end IS NULL", array($login_id));

    if (!empty($break_data)) {
        $break_start_time = strtotime($break_data[0]['break_start']);
        $current_time = time();
        $seconds_elapsed = $current_time - $break_start_time;

        $time_remaining = max(0, $total_break_time - $seconds_elapsed);

        $response = array('status' => 'success', 'time_remaining' => $time_remaining);
    } else {
        $response = array('status' => 'success', 'time_remaining' => $total_break_time);
    }

    echo json_encode($response);

?>
