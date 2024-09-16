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


$task_title = $_POST['task_title'];
$task_desc = $_POST['task_desc'];
$task_date = date('Y-m-d',strtotime($_POST['task_date']));
$due_date = date('Y-m-d',strtotime($_POST['due_date']));
$created_at = date('Y-m-d H:i:s a');

$add_task_sql = manage(
    "INSERT INTO task (user_id,title,description,assign_by,status,task_date,due_date,date_created) 
    VALUES (?,?,?,?,?,?,?,?)
    ",array($user_id,$task_title,$task_desc,'','1',$task_date,$due_date,$created_at));

$logs_result = manage(
    "INSERT INTO logs (computer_name,ip_address,page,action,details,date) 
    VALUES (?,?,?,?,?,?)",
    array(
        gethostbyaddr($_SERVER['REMOTE_ADDR']),
        $ip_address_2,              
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