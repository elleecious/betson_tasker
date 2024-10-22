<?php
    include('../includes/connect.php');

    session_start();
    $login_id = $_SESSION['login_id'];

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'Invalid request.');

    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $break_end = date('Y-m-d H:i:s');
    $break_time = isset($_POST['break_time']) ? $_POST['break_time'] : 0;

    if ($action == 'end_break') {
        $update_break = manage("UPDATE breaks SET break_end = ?, break_time = ? WHERE user_id = ? AND break_end IS NULL",
            array($break_end, $break_time, $login_id));

        if ($update_break) {
            $update_status = manage("UPDATE users SET status = ? WHERE id = ?",array('active', $login_id));

            if ($update_status) {
                $response = array('status' => 'success', 'message' => 'Break ended successfully.');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to update user status on end break.');
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update break record.');
        }
    } else if ($_POST['action'] === 'automatic_end_break') {
        
        $update_end_break_automatic = manage("UPDATE breaks SET break_end = ?, break_time = ? WHERE user_id=? AND break_end IS NULL", array($break_end, $break_time, $login_id));

        if ($update_end_break_automatic) {

            $update_end_break_status = manage("UPDATE users SET status = ? WHERE id = ?",array('active', $login_id));

            if ($update_end_break_status) {
                $response = array('status' => 'success', 'message' => 'Break ended automatically due to time limit.');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to update end break user status.');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to end break automatically');
        }
    }
    
    else {
        $response = array('status' => 'error', 'message' => 'Invalid action.');
    }

    echo json_encode($response);
?>
