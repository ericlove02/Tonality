<?php
$post = $_POST['id'];

include "db_userdata_connect.php";
$usersqli->query("UPDATE posts SET is_flagged = True WHERE post_id = ".$post);