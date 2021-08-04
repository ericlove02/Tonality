<?php
$first_name = $_POST["first"];
$last_name = $_POST["last"];
$user_name = $_POST["user"];
$email = $_POST["email"];
if (!($_POST["img"]) == "") {
    $image = $_POST["img"];
}
else {
    $image = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png";
}
if (!(@getimagesize($image))) {
    $image = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png";
}

include "db_userdata_connect.php";
if (!isset($_SESSION)) {
    session_start();
}
$userID = $_SESSION["userID"];
$emailresult = $usersqli->query("SELECT userID FROM data WHERE email=$email");
$userresult = $usersqli->query("SELECT userID FROM data WHERE user_name=$user_name");

if ($emailresult->num_rows > 0 && $emailresult->fetch_assoc() ["email"] != $userID) {
    header('Location: /update-profile?error=email');
}
else if ($userresult->num_rows > 0 && $userresult->fetch_assoc() ["user_name"] != $userID) {
    header('Location: /update-profile?error=user');
}
else {
    echo $sql = "UPDATE data SET first_name=\"$first_name\", last_name=\"$last_name\", user_name=\"$user_name\", email=\"$email\", user_image=\"$image\" WHERE userID=$userID";
    $result = $usersqli->query($sql);
    header('Location: /update-profile');
}
?>
