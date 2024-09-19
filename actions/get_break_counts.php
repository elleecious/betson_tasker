<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);


include('../includes/connect.php');

header('Content-Type: application/json');

$counts = array(
    'work_mode' => 0,
    'lunch_break' => 0,
    'short_break' => 0
);

$work_mode_count = retrieve("SELECT COUNT(*) AS count FROM users WHERE status=?",array("active"));
$lunch_break_count = retrieve("SELECT COUNT(*) AS count FROM breaks WHERE break_end IS NULL AND break_start BETWEEN NOW() - INTERVAL 1 HOUR AND NOW()",array());

$counts['work_mode'] = $work_mode_count[0]['count'];
$counts['lunch_break'] = $lunch_break_count[0]['count'];
$counts['short_break'] = $lunch_break_count[0]['count'] - $counts['lunch_break']; // Assuming the rest are short breaks

$response = array('status' => 'success', 'counts' => $counts);

echo json_encode($response);

?>
