<?php
$review = $_POST['id'];

include "db_userdata_connect.php";
$usersqli->query("UPDATE reviews SET is_flagged = True WHERE review_id = ".$review);