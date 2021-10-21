<?php
echo "do pw reset";

if (!(isset($_SESSION))) {
    session_start();
}
$hashed_user = $_SESSION['hu'];
$user_name = $_POST['user'];
$user_password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

if (password_verify($user_name, $hashed_user)) {
    include "db_userdata_connect.php";
    $usersqli->query("UPDATE data SET password='" . $user_password . "' WHERE user_name='" . $user_name . "'");

    //$_SESSION["user"] = $user_name;
    //$_SESSION["pass"] = $user_password;

    //include "signinuser.php";
	header('Location: /login?message=pw-success');
}
else {
    // failed
    header('Location: /password-reset?message=user&hu='.$_SESSION['hu']);
}

?>
