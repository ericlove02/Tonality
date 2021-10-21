<?php

$review_id = $_GET['review'];
session_start();
$user = $_SESSION['userID'];
if(isset($_GET['song'])){$song = $_GET['song'];}

include "db_userdata_connect.php";
$results = $usersqli->query("SELECT * FROM reviews WHERE review_id = $review_id");

$row = $results->fetch_assoc();
$user_level = $usersqli->query("SELECT user_level FROM data WHERE userID = $user")->fetch_assoc()['user_level'];

if($user == $row['review_by'] || $user_level >= 2){
	$usersqli->query("DELETE FROM reviews WHERE review_id=$review_id");
	$usersqli->query("DELETE FROM likes WHERE liked_review=$review_id");
}

if(isset($song)){
	header('Location: /song?id='.$song);
}else{
	header('Location: /profile?id='.$user);
}

?>