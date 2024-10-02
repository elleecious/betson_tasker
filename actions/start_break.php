<?php
    include('../includes/connect.php');

    session_start();
    $login_id = $_SESSION['login_id'];

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'Invalid request.');

    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $start_time = date('Y-m-d H:i:s');

    if ($action == 'start_break') {
        // Check if the user is already on a break
        $existing_break = retrieve("SELECT break_time, break_start FROM breaks WHERE user_id = ? AND break_end IS NULL", array($login_id));

        if (!empty($existing_break)) {
            // If already on a break, calculate the remaining break time
            $break_start_time = strtotime($existing_break[0]['break_start']);
            $current_time = time();
            $seconds_elapsed = $current_time - $break_start_time;
            $remaining_time = max(0, $existing_break[0]['break_time'] - $seconds_elapsed);

            $response = array('status' => 'success', 'message' => 'Break already started.', 'break_time' => $remaining_time);
        } else {
            // If no ongoing break, insert a new break record
            $insert_break = manage("INSERT INTO breaks (user_id, break_type, break_time, break_start) VALUES (?, ?, ?, ?)", 
                array($login_id, 'short', 3600, $start_time)
            );

            if ($insert_break) {
                $update_status = manage("UPDATE users SET status = ? WHERE id = ?", array('on_break', $login_id));
                if ($update_status) {
                    $response = array('status' => 'success', 'message' => 'Break started successfully.', 'break_time' => 3600);
                } else {
                    $response = array('status' => 'error', 'message' => 'Failed to update user status.');
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to insert break record.');
            }
        }
    }

    echo json_encode($response);
?>