<?php
require '../vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();

session_start();

$api->setAccessToken($_SESSION['token']);
$song = $_GET['id'];
$_SESSION['queue_list'][] = $song;
$api->queue($song, $_SESSION['playback_device']);

?>