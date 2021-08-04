<?php
//reply.php
include 'db_userdata_connect.php';
if (!(isset($_SESSION))) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //someone is calling the file directly, which we don't want
    echo 'This file cannot be called directly.';
}
else {
    //check for sign in status
    if (!$_SESSION['signed_in']) {
        echo 'You must be signed in to post a reply.';
    }
    else {
        //a real user posted a real reply
        $sql = "INSERT INTO 
					posts(post_content,
						  post_date,
						  post_topic,
						  post_by) 
				VALUES ('" . addslashes($_POST['reply-content']) . "',
						NOW(),
						" . $_GET['id'] . ",
						" . $_SESSION['userID'] . ")";

        $result = $usersqli->query($sql);

        if (!$result) {
            echo 'Your reply has not been saved, please try again later.';
        }
        else {
            header('Location: /topic?id=' . $_GET['id'] . '#bottom');
        }
    }
}

?>
