<?php
include('../includes/connect.php');
include('../includes/session.php');

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request.');

$break_start = date('Y-m-d H:i:s');


$insert_break = manage("INSERT INTO breaks (user_id, break_start) VALUES (?, ?)", array($login_id, $break_start));
$update_user_status = manage("UPDATE users SET status = ? WHERE id = ?", array($login_id,"on_break"));

if ($insert_break && $update_user_status) {
    echo json_encode(array('status' => 'success', 'message' => 'Break started'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Failed to start break'));
}
?>
