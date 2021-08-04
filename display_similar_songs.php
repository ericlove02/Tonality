<?php
require_once "rating_system.php";
include "db_songs_connect.php";
include "api_connect.php";
$song_num = 1;
$spotifyID = $_SESSION['current_song_spotifyid'];
$results = $api->getRecommendations(['seed_tracks' => [$spotifyID], 'limit'=>5]);

?>
<div class="row">
    <div class="col-md-12">
        <div class="boxed boxed--lg boxed--border">
            <h4>Similar Songs</h4>
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
                        <td class="font-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo mb_substr($trackName,0,20,'utf-8'); ?></td>
                        <td class="font-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $trackArtist; ?></td>
                        <td>
                            <a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>"><img src="images/spotify.svg" class="img-responsive" height="30"  /></a>
                        </td>
						<?php
						if(isset($_SESSION['spotify_connected']) && $_SESSION['spotify_connected'] && $_SESSION['isPremium']){
							echo "<td><a target=\"target\" href=\"playbackFunctions/queue.php?id=".$spotifyID."\" onclick='showAlert()' style='color:black'><div class='queue-button' style='width:fit-content;display:inline-block;margin-left:8px;'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"currentColor\" class=\"bi bi-music-note\" viewBox=\"0 0 16 16\">
							  <path d=\"M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2z\"/>
							  <path fill-rule=\"evenodd\" d=\"M9 3v10H8V3h1z\"/>
							  <path d=\"M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5V2.82z\"/>
								</svg>
								<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"18\" fill=\"currentColor\" class=\"bi bi-plus\" viewBox=\"0 0 16 16\" style='margin-left:-13px;margin-bottom:-5px;'>
							<path d=\"M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z\"/>
							</svg></div></a></td>";
							echo '<style>.alert{position:fixed;min-width:180px;z-index:1;top:100px;background-color:#74e38c;border-radius:5px;width:20%;height:40px;border:2px solid #6dad7b;color:white;left:40%;}</style><div class="alert alert-success" id="success-alert" style="display:none"><p style="width:fit-content;margin:-14px auto;font-size:14px;padding:5px;">Successfully Queued</p></div>';
							echo '<script>
							function showAlert(){
								document.getElementById("success-alert").style = "display:block";
								setTimeout(function() { document.getElementById("success-alert").style = "display:none"; }, 3000);
							}
							</script>';
						}
						?>
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
                                        echo '<a data-tooltip="Signin"><img src="images/trash-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRatings, $pop).'%';
                                    }
                                    echo '<a href="login" data-tooltip="Signin"><i class="fa fa-chevron-down"></i></a>';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php $song_num++;  }  ?>
                </tbody>
            </table> 
        </div>
    </div>
</div>
<iframe style="display:none;" name="target"></iframe>