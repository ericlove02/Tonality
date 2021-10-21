<?php
require '../vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();

session_start();

$api->setAccessToken($_SESSION['token']);

$api->next($_SESSION['playback_device']);

?>