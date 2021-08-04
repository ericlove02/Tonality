<?php
include "db_userdata_connect.php";
if (!isset($_SESSION)) {
    session_start();
}

$userUnfollowing = $_POST["user"];
$gettingUnfollowed = $_POST["unfollowing"];
if ($userUnfollowing == $_SESSION['userID']) {
    $usersqli->query("DELETE FROM follows WHERE follower_id=$userUnfollowing AND following_id=$gettingUnfollowed");
}
$usersqli->close();
//header('Location: /profile?id=' . $_GET["unfollowing"]);
?>
