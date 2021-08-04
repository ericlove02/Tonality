<?php
$post = $_GET['id'];

include "db_userdata_connect.php";
$usersqli->query("UPDATE posts SET is_flagged = False WHERE post_id = ".$post);