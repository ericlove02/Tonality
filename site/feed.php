<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <?php
            include "db_userdata_connect.php";
            include "db_songs_connect.php";
            include "api_connect.php";
            require_once "rating_system.php";
            if (!isset($_SESSION)) {
                session_start();
            }
            $results = $usersqli->query("SELECT user_votes.song_id,
                                    user_votes.voted_at,
                                    user_votes.vote_type,
                                    user_votes.vote_by
                            FROM user_votes
                            LEFT JOIN follows
                            ON follows.following_id = user_votes.vote_by
                            WHERE follows.follower_id = ".$_SESSION['userID']." 
                            ORDER BY user_votes.voted_at DESC
                            LIMIT 50");
            function humanTiming ($time){
                $time = time() - $time; // to get the time since that moment
                $time = ($time<1)? 1 : $time;
                $tokens = array (
                    31536000 => 'y',
                    2592000 => 'm',
                    604800 => 'w',
                    86400 => 'd',
                    3600 => 'h',
                    60 => 'm',
                    1 => 's'
                );

                foreach ($tokens as $unit => $text) {
                    if ($time < $unit) continue;
                    $numberOfUnits = floor($time / $unit);
                    return $numberOfUnits.$text;
                }
            }
        ?>
        <div class="main-container">
            <section class="bg--secondary space--sm">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>Votes From your Following</h2>
                        </div>
                    </div>
                    <?php
                       if($results->num_rows > 0){
                        $songs = array();
                        while($row = $results->fetch_assoc()){
                            $spotifyID = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID = ".$row['song_id'])->fetch_assoc()['spotify_uri'];
                            array_push($songs, $spotifyID);
                            $songIDs[] = $row['song_id'];
                            $times[] = $row['voted_at'];
                            $type[] = $row['vote_type'];
                            $user[] = $row['vote_by'];
                        }
                        $index = 0;
                        $tracks = $api->getTracks($songs);
                        foreach ($tracks->tracks as $track) {
                            $spotifyID = $track->id;
                            $trackName = $track->name;
                            $trackArtist = $track->artists[0]->name;
                            $artistID = $track->artists[0]->id;
                            $cover = $track->album->images[0]->url;
                            $result = $mysqli->query("SELECT songID FROM ratings WHERE spotify_uri='" . $spotifyID . "'");
                            if ($result->num_rows < 1) {
                                $mysqli->query("INSERT INTO `ratings`(`songID`, `spotify_uri`, `song_pos_ratings`, `song_total_ratings`) VALUES (NULL, '" . $spotifyID . "', 0, 0)");
                            }
                            $result = $mysqli->query("SELECT songID, song_pos_ratings, song_total_ratings FROM ratings WHERE spotify_uri='$spotifyID'");
                            $row = $result->fetch_assoc();
                            $songID = $songIDs[$index];
                            $posRating = $row["song_pos_ratings"];
                            $totRatings = $row["song_total_ratings"];
                            $pop = $track->popularity;
                            
                            $userrow = $usersqli->query("SELECT user_name, user_image FROM data WHERE userID = ".$user[$index])->fetch_assoc();
                        ?>
                        <div class="row"> 
                            <div class="col-lg-12">
                                <div class="boxed boxed--lg boxed--border">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="boxed boxed--border">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <img alt="avatar" src="<?php echo $userrow['user_image']; ?>" class="image--sm"/>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <h5 class="mgb-0 text-right"><?php echo humanTiming(strtotime($times[$index])); ?></h5>
                                                        <a href="profile?id=<?php echo $user[$index]; ?>">
                                                            @<?php echo $userrow['user_name']; ?>
                                                        </a>
                                                        <span>
                                                            <?php if($type[$index]){ 
                                                                echo " gassed: ";
                                                            } else {
                                                                echo " trashed: ";
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="boxed boxed--border">
                                                <div class="row">
                                                    <div class="col-md-1 col-sm-2">
                                                        <div class="text-center grid-cls">
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
                                                            } else {
                                                               
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
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-sm-4 clickable-row margin-35" data-href="song?id=<?php echo $spotifyID; ?>">
                                                        <img alt="avatar" src="<?php echo $cover; ?>"/>
                                                    </div>
                                                    <div class="col-md-9 col-sm-12 margin-35">
                                                        <h5 class="mgb-0 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $trackName; ?></h5>
                                                        <a href="artist?id=<?php echo $artistID; ?>">
                                                            @<?php echo $trackArtist; ?>
                                                        </a><br><br>
                                                        <div class="col-md-6">
															<?php
															if(isset($_SESSION['spotify_connected']) && $_SESSION['spotify_connected'] && $_SESSION['isPremium']){
																echo "<a target=\"target\" href=\"playbackFunctions/queue.php?id=".$spotifyID."\" onclick='showAlert()' style='color:black'><button class='btn btn--primary' style='width:fit-content;display:inline-block;margin-bottom:20px;padding:10px;font-size:14px;'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"currentColor\" class=\"bi bi-music-note\" viewBox=\"0 0 16 16\">
															  <path d=\"M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2z\"/>
															  <path fill-rule=\"evenodd\" d=\"M9 3v10H8V3h1z\"/>
															  <path d=\"M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5V2.82z\"/>
																</svg>
																<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"18\" fill=\"currentColor\" class=\"bi bi-plus\" viewBox=\"0 0 16 16\" style='margin-left:-13px;margin-bottom:-5px;'>
															<path d=\"M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z\"/>
															</svg>  Queue</button></a>";
																echo '<style>.alert{position:fixed;min-width:180px;z-index:1;top:100px;background-color:#74e38c;border-radius:5px;width:20%;height:40px;border:2px solid #6dad7b;color:white;left:40%;}</style><div class="alert alert-success" id="success-alert" style="display:none"><p style="width:fit-content;margin:-14px auto;font-size:14px;padding:5px;">Successfully Queued</p></div>';
																echo '<script>
																function showAlert(){
																	document.getElementById("success-alert").style = "display:block";
																	setTimeout(function() { document.getElementById("success-alert").style = "display:none"; }, 3000);
																}
																</script>';
															}
															?>
															<iframe style="min-height: unset;" height="80px" src="https://open.spotify.com/embed/track/<?php echo $spotifyID; ?>" width="300" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
														</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $index++; } ?>
                    <?php } else { ?>
                        <div class="col-lg-12">
                            <p class="lead">Follow some more people to fill your feed!</p>
                        </div>
                    <?php } ?>
                    <div class="col-lg-12">
                        <h2>Songs Recommended For You <img src="images/refresh.png" onclick="reload()" class="refresh"/></h2>
                    </div>
                    <?php  
                        if(isset($_SESSION["userID"]) && $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " ORDER BY voted_at DESC")->num_rows >= 1){
                        $gassedSongs = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " ORDER BY voted_at DESC LIMIT 5");
                        $songs = array();
                        while($row = $gassedSongs->fetch_assoc()){
                            $songID = $row['song_id'];
                            $spotifyID = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . trim($songID))->fetch_assoc() ['spotify_uri'];
                            array_push($songs, $spotifyID);
                        }

                        $results = $api->getRecommendations(['seed_tracks' => $songs, 'limit' => 20]);
                        $songList = array();
                    ?>
                        <?php 
                            foreach ($results->tracks as $track) {
                                $spotifyID = $track->id;
                                array_push($songList, $spotifyID);
                                $trackName = $track->name;
                                $trackArtist = $track->artists[0]->name;
                                $artistID = $track->artists[0]->id;
                                $cover = $track->album->images[0]->url;
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
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="boxed boxed--border">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="text-center grid-cls">
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
                                                        
                                                    } else {
                                                        
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
                                                </div>
                                            </div>
                                            <div class="col-md-2 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
                                                <img alt="avatar" src="<?php echo $cover; ?>"/>
                                            </div>
                                            <div class="col-md-9">
                                                <h5 class="mgb-0 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $trackName; ?></h5>
                                                <a href="artist?id=<?php echo $artistID; ?>">
                                                    @<?php echo $trackArtist; ?>
                                                </a><br><br>
                                                <div class="col-md-6">
													<?php
													if(isset($_SESSION['spotify_connected']) && $_SESSION['spotify_connected'] && $_SESSION['isPremium']){
														echo "<a target=\"target\" href=\"playbackFunctions/queue.php?id=".$spotifyID."\" onclick='showAlert()' style='color:black'><button class='btn btn--primary' style='width:fit-content;display:inline-block;margin-bottom:20px;padding:10px;font-size:14px;'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"currentColor\" class=\"bi bi-music-note\" viewBox=\"0 0 16 16\">
													  <path d=\"M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2z\"/>
													  <path fill-rule=\"evenodd\" d=\"M9 3v10H8V3h1z\"/>
													  <path d=\"M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5V2.82z\"/>
														</svg>
														<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"18\" fill=\"currentColor\" class=\"bi bi-plus\" viewBox=\"0 0 16 16\" style='margin-left:-13px;margin-bottom:-5px;'>
													<path d=\"M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z\"/>
													</svg>  Queue</button></a>";
														echo '<style>.alert{position:fixed;min-width:180px;z-index:1;top:100px;background-color:#74e38c;border-radius:5px;width:20%;height:40px;border:2px solid #6dad7b;color:white;left:40%;}</style><div class="alert alert-success" id="success-alert" style="display:none"><p style="width:fit-content;margin:-14px auto;font-size:14px;padding:5px;">Successfully Queued</p></div>';
														echo '<script>
														function showAlert(){
															document.getElementById("success-alert").style = "display:block";
															setTimeout(function() { document.getElementById("success-alert").style = "display:none"; }, 3000);
														}
														</script>';
													}
													?>
													<iframe style="min-height: unset;" height="80px" src="https://open.spotify.com/embed/track/<?php echo $spotifyID; ?>" width="300" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn--primary" onclick='reload()'>Get More Recommendations</button>
                        </div>
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
        </div>
        <iframe style="display:none;" name="target"></iframe>
        <?php
            include "footer.php";
            include "script.php";
        ?>
    </body>
</html>