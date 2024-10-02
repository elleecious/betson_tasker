<?php
    include('../includes/connect.php');

    session_start();
    $login_id = $_SESSION['login_id'];

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'Failed to retrieve remaining break time.');

    $break_data = retrieve("SELECT break_time FROM breaks WHERE user_id = ? AND break_end IS NULL", array($login_id));

    if (!empty($break_data)) {
        
        $remaining_time = $break_data[0]['break_time'];
        $response = array('status' => 'success', 'time_remaining' => $remaining_time);
    } else {
        $response = array('status' => 'success', 'time_remaining' => 3600);
    }

    echo json_encode($response);

?>
