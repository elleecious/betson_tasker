<?php
    include_once('connect.php');
    session_start();

    if (isset($_SESSION['login_id'])) {
        $login_id = $_SESSION['login_id'];
        $get_account = retrieve("SELECT * FROM users WHERE id=?", array($login_id));

        if ($get_account) {
            $user = $get_account[0];
            $user_id = $user['id'];
            $name = $user['firstname'] . " " . $user['lastname'];
            $position = $user['position'];
            $level = $user['level'];

            echo "Success";
        } else {
            // Handle case where user is not found
            $name = $position = $level = 'Unknown';
            echo "Unknown";
        }
    } else {
        // Handle case where session ID is not set
        $name = $position = $level = 'Not logged in';
        echo "Not logged in";
    }
    
?>
