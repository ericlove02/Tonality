<?php

$type = $_POST['type'];
$author = $_POST['author'];
$title = $_POST['title'];
$cover_link = $_POST['cover-link'];
$link = $_POST['link'];
$content = addslashes($_POST['content']);

include "db_userdata_connect.php";

$usersqli->query("INSERT INTO `articles`(`article_id`, `type`, `author`, `title`, `cover_image`, `content`, `link`) VALUES (NULL,'$type','$author','$title','$cover_link','$content','$link')");

$usersqli->close();

header('Location: add_article');
die();
?>