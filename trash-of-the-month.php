<?php
include "db_songs_connect.php";
require_once "rating_system.php";
include "api_connect.php";

$song_num = 1;

$sql = "SELECT * FROM ratings WHERE ratings_this_month > 0 ORDER BY (pos_ratings_this_month)/(ratings_this_month) ASC LIMIT 50";
$result = $mysqli->query($sql);
$mysqli->close();
include "db_userdata_connect.php";

$songs = array();
while ($row = $result->fetch_assoc()) {
    array_push($songs, $row['spotify_uri']);
    $spotifyIDs[] = $row['spotify_uri'];
    $songIDs[] = $row["songID"];
    $posRatings[] = $row["pos_ratings_this_month"];
    $totRatings[] = $row["ratings_this_month"];
}

$tracks = $api->getTracks($songs);

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
                    <a href="https://open.spotify.com/playlist/5sZo4NeQPdzy6g6vTJq1v9?si=66457785ca2f44fc" target="_blank"><img src="images/tom.png" style="height:200px;width:200px;display:inline-block;" /></a>
                    <span style="display:inline-block;padding-left:10px"><h3 style="display:inline-block;">Trash of the Month</h3><br>
                    The bottom 100 songs based on their percentage gassed from votes cast this month</span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table>
                            <tbody>
                                <?php  
                                    $index = 0;
                                    $songList = array();
                                    foreach ($tracks->tracks as $track) {
                                        $spotifyID = $spotifyIDs[$index];
                                        array_push($songList, $spotifyID);
                                        $trackName = $track->name;
                                        $trackArtist = $track->artists[0]->name;
                                        $songID = $songIDs[$index];
                                        $posRating = $posRatings[$index];
                                        $totRating = $totRatings[$index];
                                        $pop = $track->popularity;
                                        $cover = $track
                                            ->album
                                            ->images[0]->url;

                                        $index++;
                                if(($song_num - 1) % 10 == 0 && $song_num != 1){ ?>
											<tr>
												<td colspan="7">
												<div>
													<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
													<ins class="adsbygoogle"
														 style="display:block"
														 data-ad-format="fluid"
														 data-ad-layout-key="-fb+5w+4e-db+86"
														 data-ad-client="ca-pub-3114841394059389"
														 data-ad-slot="3561903467"></ins>
													<script>
														 (adsbygoogle = window.adsbygoogle || []).push({});
													</script><br>
													<span class="font-12">Sponsored</span>
													</div>
													
												</td>
											</tr>
										<?php
										}
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

                                                if (get_rating_no_pop($posRating, $totRating) >= 60) {
                                                    echo "<img src=\"images/gas-icon.png\" class=\"rating-icon\"/><strong>" . get_rating_no_pop($posRating, $totRating) . "%</strong>";
                                                }
                                                elseif (get_rating_no_pop($posRating, $totRating) <= 60) {
                                                    echo "<img src=\"images/trash-icon.png\" class=\"rating-icon\"/><strong>" . get_rating_no_pop($posRating, $totRating) . "%</strong>";
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
                                            
                                                if (get_rating_no_pop($posRating, $totRating)) {
                                                    echo '<a data-tooltip="Signin"><img data-tooltip="Signin" src="images/gas-icon.png" class="img-responsive" height="30"  /></a>'.get_rating_no_pop($posRating, $totRating).'%';
                                                }
                                                elseif (get_rating_no_pop($posRating, $totRating)) {
                                                    echo '<a data-tooltip="Signin"><img src="images/trash-icon.png" class="img-responsive" height="30"  /></a>'.get_rating_no_pop($posRating, $totRating).'%';
                                                }
                                                
                                                echo '<a href="login" data-tooltip="Signin"><i class="fa fa-chevron-down"></i></a>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php $song_num++; } 
                                    if(!isset($_SESSION)){
                                        session_start();
                                    }
                                    unset($_SESSION['songlist']);
                                    $_SESSION['songlist'] = $songList;

                                    $usersqli->close();
                                ?>
                            </tbody>
                        </table>
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