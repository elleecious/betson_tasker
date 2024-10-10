<?php
include('../includes/connect.php');
session_start();

$login_id = $_SESSION['login_id'];

header('Content-Type: application/json');

$response = array('status' => 'active', 'message' => 'User is not on break.');

$currentDateTime = new DateTime();
$shiftStart = clone $currentDateTime;
$shiftStart->setTime(21, 0, 0);
$shiftEnd = clone $shiftStart;
$shiftEnd->modify('+9 hours');

if ($currentDateTime < $shiftStart) {
    $shiftStart->modify('-1 day');
    $shiftEnd->modify('-1 day');
}

$last_break_query = retrieve(
    "SELECT MAX(last_break_date) as last_break_date, SUM(TIMESTAMPDIFF(SECOND, break_start, break_end)) as total_break_time 
     FROM breaks WHERE user_id = ? AND last_break_date >= ?",
    array($login_id, $shiftStart->format('Y-m-d H:i:s'))
);

$last_break_date = $last_break_query ? $last_break_query[0]['last_break_date'] : null;
$total_break_time = $last_break_query ? $last_break_query[0]['total_break_time'] : null;

if (!$last_break_date || $last_break_date < $shiftStart->format('Y-m-d H:i:s')) {
    $total_break_time = 0;
}

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
