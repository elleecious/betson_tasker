<?php
include('../includes/connect.php');

header('Content-Type: application/json');

session_start();
$login_id = $_SESSION['login_id'];

$response = array('status' => 'error', 'message' => 'Invalid request.');

$break_end = date('Y-m-d H:i:s');


$update_break = manage("UPDATE breaks SET break_end = ? WHERE user_id = ? AND break_end IS NULL", array($break_end, $login_id));
$update_user_status = manage("UPDATE users SET status = ? WHERE id = ?", array($login_id,"active"));

if ($update_break && $update_user_status) {
    echo json_encode(array('status' => 'success', 'message' => 'Break ended'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Failed to end break'));
}
?>
