<?php
if (!isset($_SESSION)) {
	session_start();
}
if (isset($_POST["user"]) && !isset($input_password) && !isset($user_name)) {
    $user_name = $_POST["user"];
	$user_name = str_replace(' ', '', $user_name);
    $input_password = $_POST["pass"];
	$doRemember = ($_POST["rem"] == 'on');
}
include "db_userdata_connect.php";

$result = $usersqli->query("SELECT userID, password, user_name, user_level FROM data WHERE user_name=\"" . $user_name . "\"");
$result2 = $usersqli->query("SELECT userID, password, user_name, user_level FROM data WHERE user_name=\"" . $_SESSION["user"] . "\"");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (password_verify($input_password, $row["password"]) || $row["password"] == $input_password) {
            /* username and password exist and correct */
            $_SESSION["userID"] = $row["userID"];
            $_SESSION['signed_in'] = True;
            $_SESSION['user_level'] = $row['user_level'];
            $_SESSION['user_name'] = $row['user_name'];
			if($doRemember){
				setcookie("pw", $row["password"], time() + (86400 * 90), "/");
				setcookie("un", $row["user_name"], time() + (86400 * 90), "/");
			}
            $usersqli->close();
            header('Location: /home');
        }
        else {
            /* user exists but inncorrect password */
            $usersqli->close();
            header('Location: /login?message=pw');
        }
    }
}
else if ($result2->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION["userID"] = $row["userID"];
        $_SESSION['signed_in'] = True;
        $_SESSION['user_level'] = $row['user_level'];
        $_SESSION['user_name'] = $row['user_name'];
		if($doRemember){
			setcookie("pw", $row["password"], time() + (86400 * 90), "/");
			setcookie("un", $row["user_name"], time() + (86400 * 90), "/");
		}
        $usersqli->close();
        header('Location: /home');
    }
}
else {
    $usersqli->close();
    header('Location: /login?message=user');
}
?>
