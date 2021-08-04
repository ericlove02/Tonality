<?php

$review_id = $_POST['review'];
session_start();
$user = $_SESSION['userID'];
if(isset($_POST['song'])){
	$song = $_POST['song'];
}
if (isset($_POST['profile'])) {
    $profile = $_POST['profile'];
}

include "db_userdata_connect.php";

$results = $usersqli->query("SELECT * FROM likes WHERE liked_review=$review_id AND liked_by=$user");
while($row = $results->fetch_assoc()){
	$usersqli->query("DELETE FROM likes WHERE liked_review=$review_id AND liked_by=$user");
	$likesResults = $usersqli->query("SELECT * FROM likes WHERE liked_review = $review_id");
	$numLikes = $likesResults->num_rows;
	$usersqli->query("UPDATE reviews SET review_likes = $numLikes WHERE review_id=$review_id");
}

if(isset($song)){
	header('Location: /song?id='.$song);
}else{
	header('Location: /profile?id=' . $profile . '#reviews-jump');
}

?>