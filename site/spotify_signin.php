<?php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
	'47a84b81d75249a2b758130418a98d76', 
	'f35285395b0a4d28a18e99ae1e44ed1f', 
	'http://gotonality.com/callback.php'
);

$state = $session->generateState();
$options = [
	'scope' => ['user-read-recently-played', 'playlist-modify-private', 'user-modify-playback-state', 'user-read-playback-state', 'user-read-currently-playing', 'app-remote-control', 'playlist-modify-public'], 
	'state' => $state, 
	'auto_refresh' => true
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();

?>