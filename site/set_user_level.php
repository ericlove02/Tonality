<?php
$user_name = $_POST['username'];
$id = $_POST['userId'];
$lvl = $_POST['userlevel'];

include "db_userdata_connect.php";
$usersqli->query("UPDATE data SET user_level=".$lvl." WHERE userID=".$id." AND user_name=\"".$user_name."\"");
$usersqli->close();

header('Location: admin_dashboard.php');
die();