<?php
include "db_userdata_connect.php";
include "db_songs_connect.php";
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_GET["song"])) {
    $song = $_GET["song"];
}
else {
    $song = $_SESSION["current_song"];
}
$rating = $_GET["rating"] == "gas";

date_default_timezone_set("America/Chicago");
$votedat = date("Y-m-d H:i:s");
$currentMonth = date("Y-m");


$voterTrashed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=False AND song_id=$song")->num_rows > 0;
$voterGassed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=True AND song_id=$song")->num_rows > 0;

if(!$rating){
	$rating = 0;
}

if ($voterGassed && $rating) { //if song in gassed and rating pressed is gas (remove vote)
    // if trying to remove vote
    $usersqli->query("DELETE FROM user_votes WHERE vote_by=".$_SESSION["userID"]." AND song_id=$song");
}
else if ($voterTrashed && !$rating) { // if song in trashed and rating pressed is trash (remove vote)
    $usersqli->query("DELETE FROM user_votes WHERE vote_by=".$_SESSION["userID"]." AND song_id=$song");
}
else {
	
    if ($voterGassed) {
        //remove song from gassed songs
        $usersqli->query("DELETE FROM user_votes WHERE vote_by=".$_SESSION["userID"]." AND song_id=$song");
    }
    else if ($voterTrashed) {
        //remove song from trashed songs
        $usersqli->query("DELETE FROM user_votes WHERE vote_by=".$_SESSION["userID"]." AND song_id=$song");
    }
    if ($rating) {
		
		$usersqli->query("INSERT INTO user_votes(song_id, vote_by, vote_type, voted_at) VALUES (".$song.", ".$_SESSION["userID"].", ".$rating.", '".$votedat."')");
    }
    else if (!$rating) {
        $usersqli->query("INSERT INTO user_votes(song_id, vote_by, vote_type, voted_at) VALUES (".$song.", ".$_SESSION["userID"].", ".$rating.", '".$votedat."')");
    }
}


// update song rating
$totalResults = $usersqli->query("SELECT song_id FROM user_votes WHERE song_id = $song");
$numVotes = $totalResults->num_rows;
$likesResults = $usersqli->query("SELECT song_id FROM user_votes WHERE song_id = $song AND vote_type=True");
$numLikes = $likesResults->num_rows;

$totalResultsMonth = $usersqli->query("SELECT song_id FROM user_votes WHERE song_id = $song AND voted_at LIKE '%".$currentMonth."%'");
$numVotesMonth = $totalResultsMonth->num_rows;
$likesResultsMonth = $usersqli->query("SELECT song_id FROM user_votes WHERE song_id = $song AND vote_type=True AND voted_at LIKE '%".$currentMonth."%'");
$numLikesMonth = $likesResultsMonth->num_rows;

$mysqli->query("UPDATE ratings SET song_pos_ratings = $numLikes, song_total_ratings = $numVotes, pos_ratings_this_month = $numLikesMonth, ratings_this_month=$numVotesMonth WHERE songID=$song");
$usersqli->close();

$spotifyID = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . $song)->fetch_assoc() ['spotify_uri'];
$mysqli->close();
//header("Location: /song?id=" . $spotifyID);
?>
