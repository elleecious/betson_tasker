<?php
include_once('connect.php');
session_start();

$login_username = $_SESSION['login_username'];

$get_account = retrieve("SELECT * FROM users WHERE username=?", array($login_username));
$user = $get_account[0];
$name = $user['firstname'] . " " . $user['lastname'];
$position = $user['position'];
?>
