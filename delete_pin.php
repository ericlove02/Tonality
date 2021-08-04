<?php
$item_id = $_POST['item_id'];

session_start();
$user = $_SESSION['userID'];

include "db_userdata_connect.php";

$sql = "DELETE FROM pins WHERE user_id=$user AND pin_item_id='$item_id'";
$usersqli->query($sql);

?>
