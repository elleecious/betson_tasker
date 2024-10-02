<?php
    include('../includes/connect.php');
    session_start();
    $login_id = $_SESSION['login_id'];

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'User not found or no task found.');

    $task_today = retrieve("SELECT user_id, title, task_status, task_date, DATEDIFF(task_date, NOW()) AS task_today FROM task WHERE task_status =? AND user_id = ?",array("1",$login_id));

    if ($task_today) {
        $response = array('status' => 'success', 'data' => $task_today);
    } else {
        $response = array('status' => 'error', 'message' => 'Task not found');
    }

    echo json_encode($response);
?>