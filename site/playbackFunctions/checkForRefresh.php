<?php
require '../vendor/autoload.php';
$api = new SpotifyWebAPI\SpotifyWebAPI();
if(!isset($_SESSION)){
	session_start();
}
$api->setAccessToken($_SESSION['token']);
$playbackInfo = $api->getMyCurrentPlaybackInfo();
$isPlaying = $playbackInfo->is_playing;
$trackId = $playbackInfo->item->id;
$needRefresh = False;

if($isPlaying != $_POST['isPlaying'] || $trackId != $_POST['id']){
	$needRefresh = True;
}

echo $needRefresh;