<?php
include_once('connect.php');
session_start();

$login_username = $_SESSION['login_username'];

$get_account = retrieve("SELECT * FROM users WHERE username=?", array($login_username));
$user = $get_account[0];
$user_id = $user['id'];
$name = $user['firstname'] . " " . $user['lastname'];
$position = $user['position'];
?>
