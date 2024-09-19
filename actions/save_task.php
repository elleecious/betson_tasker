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

    $edit_task_id = htmlspecialchars($_POST['edit_task_id']);
    $edit_task_title = htmlspecialchars($_POST['edit_task_title']);
    $edit_task_desc = htmlspecialchars($_POST['edit_task_desc']);
    $edit_task_date = date('Y-m-d',strtotime(htmlspecialchars($_POST['edit_task_date'])));
    $edit_task_due = date('Y-m-d',strtotime(htmlspecialchars($_POST['edit_task_due'])));


    $getTask = retrieve("SELECT * FROM task WHERE id=?",array($edit_task_id));
    $task = $getTask[0];

    $save_task_sql = manage("UPDATE task SET title=?, 
        description=?, task_date=?, due_date=? WHERE id=?",
        array($edit_task_title,$edit_task_desc,$edit_task_date,$edit_task_due,$edit_task_id));


    $logs_result = manage(
        "INSERT INTO logs (computer_name,ip_address,page,action,details,date) 
        VALUES (?,?,?,?,?,?)",
        array(
            gethostbyaddr($_SERVER['REMOTE_ADDR']),
            $ip_address_2,              
            "HOME",
            "EDIT",
            "<details>
                <p>EDIT Task</p>
                    <p>
                        Name: ".$name."<br>
                        Task Title: ".$task['title']." => <span class='font-weight-bold'>".$edit_task_title."</span><br>
                        Task Description: ".$task['description']." =>  <span class='font-weight-bold'>".$edit_task_desc."</span><br>
                        Task Date Assigned: ".$task['task_date']." => <span class='font-weight-bold'>".$edit_task_date."</span><br>
                        Task Due Date: ".$task['due_date']." => <span class='font-weight-bold'>".$edit_task_due."</span><br>
                        Date Updated: ".date('Y-m-d H:i:s a')."
                    </p>
                </details>
            ", 
            date('Y-m-d H:i:s a')
        )
    );

    if ($save_task_sql && $logs_result) {
        $response = array('status' => 'success', 'message' => 'Saved Task successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to edit task');
    }

    echo json_encode($response);

?>