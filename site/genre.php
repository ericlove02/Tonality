<?php
require_once "rating_system.php";
include "db_songs_connect.php";
include "api_connect.php";
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
                <?php
                    if (isset($_GET["keyword"]) && $_GET["keyword"] != "") {
                        $keyword_from_search = $_GET["keyword"];
                        $results = $api->getRecommendations(['seed_genres' => [$keyword_from_search], 'limit' => 100]);
                    }
                    else {
                        echo "<div class='top-text text-left'><h3>No songs to display</h3></div>";
                    }
                ?>
                <?php
                    if (isset($_GET["keyword"]) && $_GET["keyword"] != "") {
                        $index = 0;
                        $songList = array();
                        foreach ($results->tracks as $track) {
                            if ($index < 10) {
                                $artists[] = $track->artists[0]->id;
                            }
                            else {
                                break;
                            }
                            $index++;
                        }
                        $artists = $api->getArtists($artists);
                    
                ?>
                <div class="top-text text-left">
                    <h3>Artists from the <?php echo $keyword_from_search; ?> genre</h3>
                    <span>Some artists from the slected genre. Don't like these? Try the button!</span>
                    <svg onclick='reload()' class='refresh' style="font-size:32px;width:32px;height:32px;margin-top:-12px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
					  <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
					  <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
					</svg>
                </div>
                
                    <?php  
                        $index = 0;
                        foreach ($results->tracks as $track) {
                            if ($index < 10) {
                                $trackArtist = $track->artists[0]->name;
                                $spotifyID = $track->artists[0]->id;
                                $image = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
                                if(isset($artists->artists[$index]->images[0]->url)){
                                    $image = $artists->artists[$index]->images[0]->url;
                                }
                                $genre = "Unknown genre";
                                if(isset($artists->artists[$index]->genres[0])){
                                    $genre = $artists->artists[$index]->genres[0];
                                }
                                $result = $mysqli->query("SELECT songID FROM ratings WHERE spotify_uri='" . $spotifyID . "'");
                                if ($result->num_rows < 1) {
                                    $mysqli->query("INSERT INTO `ratings`(`songID`, `spotify_uri`, `song_pos_ratings`, `song_total_ratings`) VALUES (NULL, '" . $spotifyID . "', 0, 0)");
                                }
                                $result = $mysqli->query("SELECT songID, song_pos_ratings, song_total_ratings FROM ratings WHERE spotify_uri='$spotifyID'");
                                $row = $result->fetch_assoc();
                                $songID = $row["songID"];
                                $posRating = $row["song_pos_ratings"];
                                $totRatings = $row["song_total_ratings"];
                                $pop = $track->popularity;
                            
                    ?>
                    <div class="row mg-20">
                        <div class="cover-img flex-quater clickable-row" data-href="artist?id=<?php echo $spotifyID; ?>">
                            <img src="<?php echo $image; ?>" class="img-responsive" height="50"  />
                        </div>
                        <div class="track-artist font-20 flex-quater clickable-row" data-href="artist?id=<?php echo $spotifyID; ?>">
                            <?php echo $trackArtist; ?>
                        </div>
                        <div class="track-genre font-20 flex-quater clickable-row" data-href="artist?id=<?php echo $spotifyID; ?>">
                            <?php echo ucfirst($genre); ?>
                        </div>
                        <div class="spotify-li flex-quater">
                            <a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>">     <img src="images/spotify.svg" class="img-responsive" height="30"  />
                            </a>
                        </div>                                     

                        <?php }
                            else if ($index == 10) {
                        ?>
                            <div class="top-text text-left">
                                <h3>Songs from the <?php echo $keyword_from_search; ?> genre</h3>
                                <span>Some songs from the slected genre. Don't like these? Try the button!</span>
								<svg onclick='reload()' class='refresh' style="font-size:32px;width:32px;height:32px;margin-top:-12px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
								  <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
								  <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
								</svg>
                            </div>
                        <?php } else if ($index >= 10) { 
                            $trackName = $track->name;
                            $spotifyID = $track->id;
                            array_push($songList, $spotifyID);
                            $cover = $track->album->images[0]->url;
                            $trackArtist = $track->artists[0]->name;
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
							if(($index) % 10 == 0 && $index != 10){ ?>
								<div class="container">
									
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
									<div class="row mg-20">
										<span class="font-12">Sponsored</span>
									</div>
								</div>
							<?php
							}
							?>
                        <div class="container">
                            <div class="row mg-20">
                                <div class="cover-img flex-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
                                    <img src="<?php echo $cover; ?>" class="img-responsive" height="50"  />
                                </div>
                                <div class="track-artist font-20 flex-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
                                    <?php if(strlen($trackName)<27){echo mb_substr($trackName,0,30,'utf-8');}else{ echo mb_substr($trackName,0,37,'utf-8') . "..."; } ?>
                                </div>
                                <div class="track-genre font-20 flex-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
                                    <?php echo $trackArtist; ?>
                                </div>
                                <div class="spotify-li flex-20">
                                    <a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>">
                                        <img src="images/spotify.svg" class="img-responsive" height="30"  />
                                    </a>
                                
								<?php
								if(isset($_SESSION['spotify_connected']) && $_SESSION['spotify_connected'] && $_SESSION['isPremium']){
									echo "<a target=\"target\" href=\"playbackFunctions/queue.php?id=".$spotifyID."\" onclick='showAlert()' style='color:black'><div class='queue-button' style='width:fit-content;display:inline-block;margin-left:8px;'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"currentColor\" class=\"bi bi-music-note\" viewBox=\"0 0 16 16\">
								  <path d=\"M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2z\"/>
								  <path fill-rule=\"evenodd\" d=\"M9 3v10H8V3h1z\"/>
								  <path d=\"M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5V2.82z\"/>
									</svg>
									<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"18\" fill=\"currentColor\" class=\"bi bi-plus\" viewBox=\"0 0 16 16\" style='margin-left:-13px;margin-bottom:-5px;'>
								<path d=\"M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z\"/>
								</svg></div></a></div>";
									echo '<style>.alert{position:fixed;min-width:180px;z-index:1;top:100px;background-color:#74e38c;border-radius:5px;width:20%;height:40px;border:2px solid #6dad7b;color:white;left:40%;}</style><div class="alert alert-success" id="success-alert" style="display:none"><p style="width:fit-content;margin:-14px auto;font-size:14px;padding:5px;">Successfully Queued</p></div>';
									echo '<script>
									function showAlert(){
										document.getElementById("success-alert").style = "display:block";
										setTimeout(function() { document.getElementById("success-alert").style = "display:none"; }, 3000);
									}
									</script>';
								}else{
									echo "</div>";
								}
							
                                    if (isset($_SESSION["userID"])) {
                                        include "db_userdata_connect.php";
                                        $voterTrashed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=False AND song_id = $songID")->num_rows > 0;
                                        $voterGassed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=True AND song_id = $songID")->num_rows > 0;
                                        if ($voterGassed) {
                                            echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"target\"><img src=\"images/up-voted.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"vote-icon margin-10 up-vote\"/></a>";
                                        }
                                        else {
                                            echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"target\"><img src=\"images/up-vote.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"vote-icon margin-10 up-vote\"/></a>";
                                        }

                                        if (get_rating($posRating, $totRatings, $pop) >= 60) {
                                            echo "<img src=\"images/gas-icon.png\" class=\"rating-icon margin-10\"/><strong class='font-20'>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
                                        }
                                        elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
                                            echo "<img src=\"images/trash-icon.png\" class=\"rating-icon margin-10\"/><strong class='font-20'>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
                                        }

                                        if ($voterTrashed) {
                                            echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"target\"><img src=\"images/down-voted.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"vote-icon margin-10 down-vote\"/></a>";
                                        }
                                        else {
                                            echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"target\"><img src=\"images/down-vote.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"vote-icon margin-10 down-vote\"/></a>";
                                        }

                                    }
                                    else {
                                        echo '<div class="font-td flex-20">';
                                        echo '<a data-tooltip="Signin" href="login"><i class="fa fa-chevron-up"></i></a>';
                                        if (get_rating($posRating, $totRatings, $pop) >= 60) {
                                            echo '<a data-tooltip="Signin"><img data-tooltip="Signin" src="images/gas-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRatings, $pop).'%';
                                        }
                                        elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
                                            echo '<a data-tooltip="Signin"><img src="images/trash-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRatings, $pop).'%';
                                        }
                                        echo '<a href="login" data-tooltip="Signin"><i class="fa fa-chevron-down"></i></a>';
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php $index++;
                        if ($index == 100) {
                            break;
                        }
                     } 
                    ?>
                <?php } 
                    if(!isset($_SESSION)){
                        session_start();
                    }
                    unset($_SESSION['songlist']);
                    $_SESSION['songlist'] = $songList;

                    $mysqli->close();   
                ?>
            </div>
        </section>
        <iframe style="display:none;" name="target"></iframe>
        <?php 
            include "footer.php";
            include "script.php";
        ?>
    </body>
</html>