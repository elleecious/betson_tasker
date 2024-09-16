<?php
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

include('../includes/connect.php');
include('../includes/session.php');

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request.');

$ip_address = getenv('HTTP_CLIENT_IP') ?: getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?: getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?: getenv('REMOTE_ADDR');
$ip_address_2 = ($_SERVER['REMOTE_ADDR'] == '::1') ? '127.0.0.1' : $ip_address;

$delete_task_id = htmlspecialchars($_POST['delete_task_id']);

$delete_sql = manage("DELETE FROM task WHERE id=?",array($delete_task_id));

if ($delete_sql) {
    $response = array('status' => 'success', 'message' => 'Deleted Task successfully');
} else {
    $response = array('status' => 'error', 'message' => 'Failed to delete task');
}

echo json_encode($response);

?>