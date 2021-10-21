<?php
if(!isset($_SESSION)) { 
	session_start(); 
}
$_SESSION["userID"] = NULL;
$_SESSION['signed_in'] = NULL;
$_SESSION['user_level'] = NULL;
$_SESSION['user_name'] = NULL;
session_destroy();

if($_COOKIE['un']){
	unset($_COOKIE['un']);
	setcookie('un', null, -1, '/'); 
}
if($_COOKIE['pw']){
	unset($_COOKIE['pw']);
	setcookie('pw', null, -1, '/'); 
}

header('Location: /index');
?>
