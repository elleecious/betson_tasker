<?php

    include('../includes/connect.php');
    include('../includes/session.php');
    include('../library/functions.php');

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'User not found or no task found.');


    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    $hashed_password = retrieve("SELECT user_password FROM users WHERE id = ?", array($login_id));

    if ($hashed_password && password_verify($current_password, $hashed_password[0]['user_password'])) {

        $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $update = manage("UPDATE users SET user_password = ? WHERE id = ?", array($new_hashed_password, $login_id));
        $logs_result = manage(
            "INSERT INTO logs (computer_name,ip_address,page,action,details,date) 
            VALUES (?,?,?,?,?,?)",
            array(
                gethostbyaddr($_SERVER['REMOTE_ADDR']),
                getPublicIP(),            
                "CHANGE PASSWORD",
                "CHANGE",
                "<details>
                    <p>CHANGE PASSWORD</p>
                        <p>Name: ".$name."</p>
                        <p>Date: ".date('Y-m-d H:i:s a')."</p>
                    </details>
                ", 
                date('Y-m-d H:i:s a')));

        if ($update && $logs_result) {
            $response = array('status' => 'success', 'message' => 'Password changed successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to change password.');
        }
    } else {
            $response = array('status' => 'error', 'message' => 'Current password is incorrect.');
    }

    echo json_encode($response);
?>
