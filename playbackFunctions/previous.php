<?php
require '../vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();

session_start();

$api->setAccessToken($_SESSION['token']);

$api->previous($_SESSION['playback_device']);

?>