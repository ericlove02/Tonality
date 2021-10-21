<?php
include "db_userdata_connect.php";
session_start(); 

$gettingFollowed = $_POST["followed"];
$followedBy = $_POST["follower"];
date_default_timezone_set("America/Chicago");
$followedAt = date("Y-m-d H:i:s");

if($_POST["follower"] == $_SESSION['userID']){
	$usersqli->query("INSERT INTO follows(follower_id, following_id, followed_at) VALUES ($followedBy, $gettingFollowed, '$followedAt')");
}
$usersqli->close();
//header('Location: /profile?id='.$_POST["followed"]);
?>