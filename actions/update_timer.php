<?php
    include('../includes/connect.php');
    session_start();

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'Failed to update timer');

    $login_id = $_SESSION['login_id'];

    $break_time_left = isset($_POST['break_time_left']) ? $_POST['break_time_left'] : 0;

    $update_timer = manage("UPDATE breaks SET break_time=? WHERE user_id=? AND break_end IS NULL",array($break_time_left,$login_id));

    if ($update_timer) {
        $response = array('status' => 'success', 'message' => 'Break time updated');
    } else {
        $response = array('status' => 'error', 'mesage' => 'Failed to update break time');
    }

    echo json_encode($response);
?>