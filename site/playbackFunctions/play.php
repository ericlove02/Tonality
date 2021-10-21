<?php
require '../vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();

session_start();

$api->setAccessToken($_SESSION['token']);
//$trackUri = "spotify:track:" . $_POST['id'];
//$position = $_POST['pos'];
//$api->play($_SESSION['playback_device'], ['uris'=>[$trackUri], 'position_ms'=>$position]);
$api->play($_SESSION['playback_device'], null);

?>