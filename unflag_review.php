<?php
$review = $_GET['id'];

include "db_userdata_connect.php";
$usersqli->query("UPDATE reviews SET is_flagged = False WHERE review_id = ".$review);