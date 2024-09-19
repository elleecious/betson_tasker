<?php

ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

include('../includes/connect.php');
include('../includes/session.php');

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request.');

$start_time = date('Y-m-d H:i:s');
$break_type = 'lunch';

if ($login_id) {
    $user = retrieve("SELECT id FROM users WHERE id = ?", array($login_id));
    $user_id = $user[0]['id'] ?? null;

    if ($user_id) {
        $insert_break = manage(
            "INSERT INTO breaks (user_id, break_type, break_start) VALUES (?, ?, ?)",
            array($login_id, $break_type, $start_time)
        );

        if ($insert_break) {
            $update_status = manage(
                "UPDATE users SET status = ? WHERE id = ?",
                array("on_break", $login_id));

            if ($update_status) {
                $response = array('status' => 'success', 'message' => 'Lunch break started successfully.');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to update break status.');
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to insert break record.');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'User not found.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'User not logged in.');
}

echo json_encode($response);
?>
