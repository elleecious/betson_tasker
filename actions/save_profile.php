<?php

    include('../includes/connect.php');
    include('../includes/session.php');
    include('../library/functions.php');
    
    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => 'Invalid request.');

    $edit_id = htmlspecialchars($_POST['edit_id']);
    $edit_lastname = htmlspecialchars($_POST['edit_lastname']);
    $edit_firstname = htmlspecialchars($_POST['edit_firstname']);
    $edit_position = htmlspecialchars($_POST['edit_position']);
    $edit_username = htmlspecialchars($_POST['edit_username']);

    $getProfileName = retrieve("SELECT * FROM users WHERE id=?",array($edit_id));
    $profile_name = $getProfileName[0];

    $save_profile_sql = manage("UPDATE users SET lastname=?, firstname=?, position=?, 
        username=?, updated_at=? WHERE id=?",
        array($edit_lastname,$edit_firstname,$edit_position,$edit_username,date('Y-m-d H:i:s a'),$edit_id));
    
    $logs_result = manage(
            "INSERT INTO logs (computer_name,ip_address,page,action,details,date) 
            VALUES (?,?,?,?,?,?)",
            array(
                gethostbyaddr($_SERVER['REMOTE_ADDR']),
                $ip_address_2,
                getPublicIP(), 
                "PROFILE",
                "EDIT",
                "<details>
                    <p>EDIT PROFILE</p>
                        <p>
                            Lastname: ".$profile_name['lastname']." => <span class='font-weight-bold'>".$edit_lastname."</span><br>
                            Firstname: ".$profile_name['firstname']." => <span class='font-weight-bold'>".$edit_firstname."</span><br>
                            Position: ".$profile_name['position']." => <span class='font-weight-bold'>".$edit_position."</span><br>
                            Username: ".$profile_name['username']." => <span class='font-weight-bold'>".$edit_username."</span><br>
                        </p>
                    </details>
                ", 
                date('Y-m-d H:i:s a')));
    
    
    if ($save_profile_sql && $logs_result) {
        $response = array('status' => 'success', 'message' => 'Profile updated successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update profile');
    }

    echo json_encode($response);

?>