<?php
ini_set('log_errors', 1);
ini_set('error_log', 'C:/xampp/php/logs/php_error_log.txt');
error_reporting(E_ALL);

include('../includes/connect.php');
include('../includes/session.php');
include('../library/functions.php');

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => 'Invalid request.');


$task_title = $_POST['task_title'];
$task_desc = $_POST['task_desc'];
$task_date = date('Y-m-d',strtotime($_POST['task_date']));
$due_date = date('Y-m-d',strtotime($_POST['due_date']));
$created_at = date('Y-m-d H:i:s a');

$add_task_sql = manage(
    "INSERT INTO task (user_id,title,description,assign_by,task_status,task_date,due_date,date_created) 
    VALUES (?,?,?,?,?,?,?,?)
    ",array($login_id,$task_title,$task_desc,'','1',$task_date,$due_date,$created_at));

$logs_result = manage(
    "INSERT INTO logs (computer_name,ip_address,page,action,details,date) 
    VALUES (?,?,?,?,?,?)",
    array(
        gethostbyaddr($_SERVER['REMOTE_ADDR']),
        getPublicIP(),          
        "HOME",
        "CREATE",         
            "<details>
                <p>Create Task</p>
                <p>
                    Name: ".$name."
                    Task Title: ".$task_title."<br>
                    Task Description: ".$task_desc."<br>
                    Task Date Assigned: ".$task_date."<br>
                    Task Due Date: ".$due_date."<br>
                    Date Created: ".$created_at."
                </p>
            </details>
        ", 
        date('Y-m-d H:i:s a')
    )
);

if ($add_task_sql && $logs_result) {
    $response = array('status' => 'success', 'message' => 'Added Task successfully');
} else {
    $response = array('status' => 'error', 'message' => 'Failed to add a task');
}

echo json_encode($response);


?>