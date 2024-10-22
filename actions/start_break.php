<?php

    include('../includes/connect.php');
    session_start();
    $login_id = $_SESSION['login_id'];

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'Invalid start break.');

    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $start_time = date('Y-m-d H:i:s');

    if ($action == 'start_break') {

        $currentDateTime = new DateTime();
        $shift_start = clone $currentDateTime;
        $shift_start->setTime(21,0,0);
        $shift_end = clone $shift_start;
        $shift_end->modify('+9 hours');

        $isNewShift = false;

        if ($currentDateTime < $shift_start) {
            $shift_start->modify('-1 day');
            $shift_end->modify('-1 day');
            $isNewShift = true;
        }
        
        $check_break_query = retrieve(
            "SELECT SUM(TIMESTAMPDIFF(SECOND, break_start, break_end)) as total_break_time 
             FROM breaks 
             WHERE user_id = ? AND break_start >= ?",
            array($login_id, $shift_start->format('Y-m-d H:i:s')));

        $total_break_time = $check_break_query ? $check_break_query[0]['total_break_time'] : 0;

        if ($total_break_time >= 3600) { 
            $response = array('status' => 'error', 'message' => 'You have already used your break time for this shift.');
        } else {

            $last_break = retrieve(
                "SELECT break_time FROM breaks WHERE user_id = ? AND break_end IS NOT NULL ORDER BY break_end DESC LIMIT 1",
                array($login_id));

            if ($last_break) {
                $break_time = $last_break[0]['break_time'] ?: 3600;
            } else {
                $break_time = 3600;
            }

            if ($break_time <= 0) {
                $response = array('status' => 'error', 'message' => 'You have no remaining break time left for this shift.');
            } else {
                $insert_break = manage(
                    "INSERT INTO breaks (user_id, break_type, break_time, break_start, last_break_date) VALUES (?, ?, ?, ?, ?)",
                    array($login_id, 'short', $break_time, $start_time, $currentDateTime->format('Y-m-d'))
                );
                if ($insert_break) {
                    $update_status = manage("UPDATE users SET status = ? WHERE id = ?", array('on_break', $login_id));
                    if ($update_status) {
                        $response = array('status' => 'success', 'message' => 'Break started successfully.', 'break_time' => $break_time);
                    } else {
                        $response = array('status' => 'error', 'message' => 'Failed to update user status on start');
                    }
                } else {
                    $response = array('status' => 'error', 'message' => 'Failed to insert break record.');
                }
            }
            
        }
    }

    echo json_encode($response);
?>
