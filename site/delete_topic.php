<?php

$topic_id = $_GET['topic'];
session_start();
$user = $_SESSION['userID'];
if(isset($_GET['category'])){
	$category = $_GET['category'];
}

include "db_userdata_connect.php";
$results = $usersqli->query("SELECT * FROM topics WHERE topic_id = $topic_id");
$row = $results->fetch_assoc();

$user_level = $usersqli->query("SELECT user_level FROM data WHERE userID = $user")->fetch_assoc()['user_level'];

if($user == $row['topic_by'] || $user_level >= 2){
	$usersqli->query("DELETE FROM topics WHERE topic_id = $topic_id");
	$usersqli->query("DELETE FROM posts WHERE post_topic = $topic_id");
}

if(isset($category)){
	header('Location: /category?id='.$category);
}else{
	header('Location: /profile?id='.$user);
}

?>