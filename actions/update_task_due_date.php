<?php

include('../includes/connect.php');
session_start();
$login_id = $_SESSION['login_id'];

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request.');

//$due_date = retrieve("SELECT id, title, DATEDIFF(due_date, task_date) AS deadline FROM task");
$due_date = retrieve("SELECT id, title, DATEDIFF(due_date, NOW()) AS deadline FROM task WHERE user_id=?",array($login_id));

if ($due_date) {
    $response = array('status' => 'success', 'data' => $due_date);
} else {
    $response = array('status' => 'error', 'message' => 'Failed to fetch data');
}

echo json_encode($response);

?>