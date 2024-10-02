<?php
    include('../includes/connect.php');
    include('../includes/session.php');

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'Invalid request.');

    $delete_task_id = htmlspecialchars($_POST['delete_task_id']);

    $delete_sql = manage("DELETE FROM task WHERE id=?",array($delete_task_id));

    if ($delete_sql) {
        $response = array('status' => 'success', 'message' => 'Deleted Task successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete task');
    }

    echo json_encode($response);

?>