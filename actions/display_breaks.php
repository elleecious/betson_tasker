<?php
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

include('../includes/connect.php');

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request.');

$totalBreakTime = 3600;

$employees = retrieve("SELECT u.id, u.firstname, u.lastname, b.break_start, b.break_end 
FROM users u LEFT JOIN breaks b ON u.id = b.user_id  WHERE u.status=? AND b.break_end IS NULL", array("on_break"));

    if ($employees !== false) {
        foreach ($employees as &$employee) {
            if ($employee['break_start']) {
                $breakStart = new DateTime($employee['break_start']);
                $now = new DateTime();
                $interval = $breakStart->diff($now);
                $secondsElapsed = $interval->s + ($interval->i * 60) + ($interval->h * 3600);
                $employee['time_remaining'] = max(0, $totalBreakTime - $secondsElapsed);
            } else {
                $employee['time_remaining'] = $totalBreakTime;
            }
        }
        $response = array(
            'status' => 'success', 
            'employees' => $employees);
    } else {
        $response = array(
            'status' => 'success', 
            'message' => 'No breaks found.');
    }

echo json_encode($response);
?>
