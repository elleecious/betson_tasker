<?php

    include('../includes/connect.php');
    session_start();

    $login_id = $_SESSION['login_id'];

    $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $shiftStart = clone $currentDateTime;
    $shiftStart->setTime(21, 0, 0);

    if ($currentDateTime < $shiftStart) {
        $shiftStart->modify('-1 day');
    }

    $shiftEnd = clone $shiftStart;
    $shiftEnd->modify('+9 hours');

    $last_break_query = retrieve(
        "SELECT MAX(last_break_date) as last_break_date FROM breaks WHERE user_id = ?",
        array($login_id)
    );

    $last_break_date = $last_break_query ? $last_break_query[0]['last_break_date'] : null;

    if ($last_break_date === null || new DateTime($last_break_date) < $shiftStart) {
        manage("UPDATE users SET total_break_time = 0 WHERE id = ?", array($login_id));
        $response = array('status' => 'success', 'message' => 'Break time reset for the new shift.');
    } else {
        $response = array('status' => 'error', 'message' => 'Break time is still valid for this shift.');
    }

    echo json_encode($response);
?>
