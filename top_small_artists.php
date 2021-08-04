<?php
require_once "rating_system.php";
include "db_songs_connect.php";
include "api_connect.php";

$song_num = 1;

$results = $api->getRecommendations(['seed_genres' => ['indie'], 'target_popularity' => 0, 'min_energy' => .5, 'limit' => 5]);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <div class="main-container">
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Recommended For You
                                <img src='images/refresh.png' onclick='reload()' class='refresh'/>
                            </h2>
                            <iframe style="display:none;" name="target"></iframe>
                            <table>
                                <tbody>
                                    <?php  
                                        foreach ($results->tracks as $track) {
                                            $spotifyID = $track->id;
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
                                        <td class="font-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $trackName; ?></td>
                                        <td class="font-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $trackArtist; ?></td>
                                        <td>
                                            <a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>"><img src="images/spotify.svg" class="img-responsive" height="30"  /></a>
                                        </td>
                                        <td class="font-td">
                                            <?php 

                                                if (isset($_SESSION["userID"])) {
                                                    include "db_userdata_connect.php";
                                                    $voteresult = $usersqli->query("SELECT gassed_songs, trashed_songs FROM data WHERE userID=" . $_SESSION["userID"]);
                                                    $usersqli->close();
                                                    if ($voteresult->num_rows > 0) {
                                                        $songs = $voteresult->fetch_assoc();
                                                    }
                                                    if (!(strpos($songs["gassed_songs"], " " . $songID . ",") === false)) {
                                                        echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"target\"><img src=\"images/up-voted.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"vote-icon\"/></a>";
                                                    }
                                                    else {
                                                        echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"target\"><img src=\"images/up-vote.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"vote-icon\"/></a>";
                                                    }

                                                    if (get_rating($posRating, $totRatings, $pop) >= 60) {
                                                        echo "<img src=\"images/gas-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
                                                    }
                                                    elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
                                                        echo "<img src=\"images/trash-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
                                                    }

                                                    if (!(strpos($songs["trashed_songs"], " " . $songID . ",") === false)) {
                                                        echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"target\"><img src=\"images/down-voted.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"vote-icon\"/></a>";
                                                    }
                                                    else {
                                                        echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"target\"><img src=\"images/down-vote.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"vote-icon\"/></a>";
                                                    }

                                                }
                                                else {
                                                    echo '<a data-tooltip="Signin" href="login"><i class="fa fa-chevron-up"></i></a>';
                                                    if (get_rating($posRating, $totRatings, $pop) >= 60) {
                                                        echo '<a data-tooltip="Signin"><img data-tooltip="Signin" src="images/gas-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRatings, $pop).'%';
                                                    }
                                                    elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
                                                        echo '<a data-tooltip="Signin"><img src="images/trash-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRatings, $pop).'%';
                                                    }
                                                    echo '<a href="login" data-tooltip="Signin"><i class="fa fa-chevron-down"></i></a>';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php $song_num++; } 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
        </div>
        <?php
            include "footer.php";
            include "script.php";
        ?>
    </body>
</html>
<iframe style="display:none;" name="target"></iframe>