<?php
include "api_connect.php";
include "db_songs_connect.php";

if (!isset($_SESSION)) {
    session_start();
}

$spotifyID = $api->getTrack($mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . $_SESSION["current_song"])->fetch_assoc() ['spotify_uri'])->id;

if (isset($_SESSION["userID"])) {
    include "db_userdata_connect.php";
    $user = $_SESSION["userID"];
    $review = addslashes($_POST["comment"]);
    $postedon = $_SESSION["current_song"];
    date_default_timezone_set("America/Chicago");
    $postedat = date("Y-m-d H:i:s");

    $sql = "INSERT INTO `reviews`(`review_id`, `review_content`, `review_date`, `review_on`, `review_by`, `review_likes`, `is_flagged`) VALUES (NULL, '" . $review . "', '" . $postedat . "', " . $postedon . ", " . $user . ", 0, False)";
    $result = $usersqli->query($sql);
    $usersqli->close();
    header('Location: /song?id=' . $spotifyID);
}
else {
    header('Location: /song?id=' . $spotifyID . '&error=signin');
}
?>
