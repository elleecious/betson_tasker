<?php
include('../includes/connect.php');

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request.');


$task_id = $_POST['task_id'];
$current_status = $_POST['current_status'];
$direction = $_POST['direction'];
$new_status = $current_status;

if ($direction === 'forward') {
    $new_status = ($current_status == 1) ? 2 : (($current_status == 2) ? 3 : 4);
} elseif ($direction === 'backward') {
    $new_status = ($current_status == 4) ? 3 : (($current_status == 3) ? 2 : 1);
} else if ($direction === "paused") {
    $new_status = 5;
}

$update_task_sql = manage("UPDATE task SET task_status = ? WHERE id = ?",array($new_status, $task_id));

if ($update_task_sql) {
    $response = array('status' => 'success', 'message' => 'Task moved successfully');
} else {
    $response = array('status' => 'error', 'message' => 'Failed to move task');
}

echo json_encode($response);
?>
