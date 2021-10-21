<?php
include "api_connect.php";
include "db_songs_connect.php";
require_once "rating_system.php";
include "db_userdata_connect.php";

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$results = explode("=on&", parse_url($url) ['query']);
$seed_songs = "";
$songNum = 0;
foreach ($results as $id) {
    if ($songNum < 5) {
        $trackID = substr($id, 0, 22);
        $seed_songs = $seed_songs . "," . $trackID;
        $songNum++;
    }
}
$seed_songs = substr($seed_songs, 1);
$results = $api->getRecommendations(['seed_tracks' => [$seed_songs], 'limit' => 100]);

$song_num = 1;
$recommendations = array();

$displayNum = 0;
$songList = array();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <section class=" ">
            <div class="container">
                <div class="top-text text-left">
                    <h3>VRS Music Discovery - Results</h3>
                    <p class="lead">Here's 20/100 of your results! Use the button to export the full playlist to your Spotify account. We hope you found some new music!</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table>
                            <tbody>
                                <?php  
                                foreach ($results->tracks as $track) {
                                    $spotifyID = $track->id;
                                    array_push($recommendations, $spotifyID);
                                    array_push($songList, $spotifyID);
                                    if ($displayNum < 20) {
                                        $displayNum++;
                                        $trackName = $track->name;
                                        $trackArtist = $track->artists[0]->name;
                                        $cover = $track
                                            ->album
                                            ->images[0]->url;
                                        $result = $mysqli->query("SELECT songID FROM ratings WHERE spotify_uri='" . $spotifyID . "'");
                                        if ($result->num_rows < 1) {
                                            $mysqli->query("INSERT INTO `ratings`(`songID`, `spotify_uri`, `song_pos_ratings`, `song_total_ratings`, `pos_ratings_this_month`, `ratings_this_month`) VALUES (NULL, '" . $spotifyID . "', 0, 0, 0, 0)");
                                        }
                                        $result = $mysqli->query("SELECT songID, song_pos_ratings, song_total_ratings FROM ratings WHERE spotify_uri='$spotifyID'");
                                        $row = $result->fetch_assoc();
                                        $songID = $row["songID"];
                                        $posRating = $row["song_pos_ratings"];
                                        $totRatings = $row["song_total_ratings"];
                                        $pop = $track->popularity;  

                                ?>
                                <tr>
                                    <td class="clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $song_num; ?></td>
                                    <td class="clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
                                        <img src="<?php echo $cover; ?>" class="img-responsive" height="50"  />
                                    </td>
                                    <td class="font-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo mb_substr($trackName,0,20,'utf-8'); ?></td>
                                    <td class="font-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $trackArtist; ?></td>
                                    <td>
                                        <a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>"><img src="images/spotify.svg" class="img-responsive" height="30"  /></a>
                                    </td>
                                    <td class="font-td">
                                        <?php 
                                            if (isset($_SESSION["userID"])) {
                                                include "db_userdata_connect.php";
                                                $voterTrashed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=False AND song_id = $songID")->num_rows > 0;
                                                $voterGassed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=True AND song_id = $songID")->num_rows > 0;
                                                if ($voterGassed) {
                                                    echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"target\"><img src=\"images/up-voted.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"vote-icon up-vote\"/></a>";
                                                }
                                                else {
                                                    echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"target\"><img src=\"images/up-vote.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"vote-icon up-vote\"/></a>";
                                                }

                                                if (get_rating($posRating, $totRatings, $pop) >= 60) {
                                                    echo "<img src=\"images/gas-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
                                                }
                                                elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
                                                    echo "<img src=\"images/trash-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
                                                }

                                                if ($voterTrashed) {
                                                    echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"target\"><img src=\"images/down-voted.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"vote-icon down-vote\"/></a>";
                                                }
                                                else {
                                                    echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"target\"><img src=\"images/down-vote.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"vote-icon down-vote\"/></a>";
                                                }

                                            }
                                            else {
                                                echo '<a data-tooltip="Signin" href="login"><i class="fa fa-chevron-up"></i></a>';
                                                if (get_rating($posRating, $totRatings, $pop) >= 60) {
                                                    echo '<a data-tooltip="Signin"><img data-tooltip="Signin" src="images/gas-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRatings, $pop).'%';
                                                }
                                                elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
                                                    echo '<a data-tooltip="Signin"><img data-tooltip="Signin" src="images/gas-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRatings, $pop).'%';
                                                }
                                                
                                                
                                                echo '<a href="login" data-tooltip="Signin"><i class="fa fa-chevron-down"></i></a>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php $song_num++; } } 
                                if (!(isset($_SESSION))) {
                                    session_start();
                                }
                                unset($_SESSION['songlist']);
                                $_SESSION['songlist'] = $songList;

                                if (!(isset($_SESSION['recommendations']))) {
                                    unset($_SESSION['recommendations']);
                                }
                                $_SESSION['recommendations'] = $recommendations;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="justify-content: center;">
                    <div class="col-md-6 text-right">
                        <a class="btn btn--primary" href="discover.php">
                            <span class="btn__text">Start Over</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        
                        <a class="btn btn--primary" href="rec-export.php" target="_blank">
                            <span class="btn__text">Export to Spotify</span>
                        </a>
                    </div>
                </div>    
            </div>
        </section>
        <iframe style="display:none;" name="target"></iframe>
        <?php 
            include "footer.php";
            include "script.php";
        ?>
    </body>
</html>