<?php

include "db_songs_connect.php";

$top100results = $mysqli->query("SELECT spotify_uri FROM ratings WHERE song_total_ratings > 0 ORDER BY (song_pos_ratings/song_total_ratings)*100 DESC LIMIT 100");
$bottom100results = $mysqli->query("SELECT spotify_uri FROM ratings WHERE song_total_ratings > 0 ORDER BY (song_pos_ratings/song_total_ratings)*100 ASC LIMIT 100");
$mostGassedresults = $mysqli->query("SELECT spotify_uri FROM ratings WHERE song_total_ratings > 0 ORDER BY song_pos_ratings DESC LIMIT 50");
$mostTrashedresults = $mysqli->query("SELECT spotify_uri FROM ratings WHERE song_total_ratings > 0 ORDER BY song_total_ratings-song_pos_ratings DESC LIMIT 50");
$gasMoresults = $mysqli->query("SELECT spotify_uri FROM ratings WHERE ratings_this_month > 0 ORDER BY (pos_ratings_this_month)/(ratings_this_month) DESC LIMIT 50");
$trashMoresults = $mysqli->query("SELECT spotify_uri FROM ratings WHERE ratings_this_month > 0 ORDER BY (pos_ratings_this_month)/(ratings_this_month) ASC LIMIT 50");
$trendingresults = $mysqli->query("SELECT spotify_uri FROM ratings WHERE ratings_this_month > 0 ORDER BY ratings_this_month DESC LIMIT 50");

$mysqli->close();

$top100Ids = array();
$bottom100Ids = array();
$mostGassedIds = array();
$mostTrashedIds = array();
$gasMoIds = array();
$trashMoIds = array();
$trendingIds = array();

$top100_uri = '00QW2oZQHoJRCdOYq605i8';
$bottom100_uri = '68AVxW4J25g9Cp6J4skm2y';
$mostgassed_uri = '4RozhKyKT0abCAvpaehM7s';
$mosttrashed_uri = '63YqZgPocMkyxy01b3E733';
$gasofmonth_uri = '3hVuauFhMdG1hNpmOKUk7t';
$trashofmonth_uri = '5sZo4NeQPdzy6g6vTJq1v9';
$trending_uri = '3jX6Kv8foxojVVqmzXK2NR';

$i = 0;
while($top100Row = $top100results->fetch_assoc()){
	array_push($top100Ids, $top100Row['spotify_uri']);
	array_push($bottom100Ids, $bottom100results->fetch_assoc()['spotify_uri']);
	if($i < 50){
		array_push($mostGassedIds, $mostGassedresults->fetch_assoc()['spotify_uri']);
		array_push($mostTrashedIds, $mostTrashedresults->fetch_assoc()['spotify_uri']);
		array_push($gasMoIds, $gasMoresults->fetch_assoc()['spotify_uri']);
		array_push($trashMoIds, $trashMoresults->fetch_assoc()['spotify_uri']);
		array_push($trendingIds, $trendingresults->fetch_assoc()['spotify_uri']);
	}
	$i++;
}

include "api_connect.php";
if(!isset($_SESSION)){
	session_start();
}
$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($_SESSION['token']);

$api->replacePlaylistTracks($top100_uri, $top100Ids);
$api->replacePlaylistTracks($bottom100_uri, $bottom100Ids);
$api->replacePlaylistTracks($mostgassed_uri, $mostGassedIds);
$api->replacePlaylistTracks($mosttrashed_uri, $mostTrashedIds);
$api->replacePlaylistTracks($gasofmonth_uri, $gasMoIds);
$api->replacePlaylistTracks($trashofmonth_uri, $trashMoIds);
$api->replacePlaylistTracks($trending_uri, $trendingIds);

echo True;

