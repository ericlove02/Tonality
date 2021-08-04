<?php

$post_id = $_GET['post'];
session_start();
$user = $_SESSION['userID'];
if(isset($_GET['topic'])){
	$topic = $_GET['topic'];
}

include "db_userdata_connect.php";
$results = $usersqli->query("SELECT * FROM posts WHERE post_id = $post_id");
$row = $results->fetch_assoc();

$user_level = $usersqli->query("SELECT user_level FROM data WHERE userID = $user")->fetch_assoc()['user_level'];

if($user == $row['post_by'] || $user_level >= 2){
	$usersqli->query("DELETE FROM posts WHERE post_id = $post_id");
}

if(isset($topic)){
	header('Location: /topic?id='.$topic);
	die();
}else{
	header('Location: /profile?id='.$user);
	die();
}

?>