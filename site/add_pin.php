<?php
$item_id = $_POST['item_id'];
$item_type = $_POST['item_type'];

session_start();
$user = $_SESSION['userID'];

include "db_userdata_connect.php";

$results = $usersqli->query("SELECT user_id FROM pins WHERE user_id=" . $_SESSION['userID']);
if($results->num_rows < 12){
	$sql = "INSERT INTO pins(user_id, pin_type, pin_item_id) VALUES (" . $user . ",'" . $item_type . "','" . $item_id . "')";
	$usersqli->query($sql);
}

?>
