<?php
    require_once "rating_system.php";
    include "db_songs_connect.php";
    include "api_connect.php";
    set_error_handler('exceptions_error_handler');
    function exceptions_error_handler($severity, $message, $filename, $lineno) {
        if (error_reporting() == 0) {
            return;
        }
        if (error_reporting() & $severity) {
            throw new ErrorException($message, 0, $severity, $filename, $lineno);
        }
    }

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
                            <div class="boxed boxed--border">
                                <form class="text-left row mx-0" name="search" id="search" action="">
                                    <div class="col-md-4">
                                        <span>Search:</span>
                                        <input type="text" name="keyword" value="<?php echo $_GET["keyword"] ?>" onchange="searchChanged(this)" />
                                    </div>
                                    <div class="col-md-4">
                                        <span>Category:</span>
                                        <select name="search-options" id="dropdown" onchange="changeAction(this)">
                                            <option value="search?keyword=" selected>Search All</option>
                                            <option value="search-users?keyword=">Users</option>
                                            <option value="search-albums?keyword=">Albums</option>
                                            <option value="search-songs?keyword=">Song Names</option>
                                            <option value="search-artists?keyword=">Artists</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4 boxed">
                                        <button type="submit" class="btn btn--primary type--uppercase">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="top-text text-left">
                        <?php 
                            if (isset($_GET["keyword"]) && $_GET["keyword"] != "") {
                                $keyword_from_search = $_GET["keyword"];
                                $results = $api->search($keyword_from_search, ['track', 'artist', 'album', 'playlist'], ['limit' => 10]);
                                $user_to_search = $_GET["keyword"];
                                $sql = "SELECT userID, user_name, first_name, last_name, user_image, user_level FROM data WHERE user_name LIKE '%" . $user_to_search . "%' OR first_name LIKE '%" . $user_to_search . "%' OR last_name LIKE '%" . $user_to_search . "%' LIMIT 10";
                            ?>
                        <?php } else { ?>
                            <h3>Use the search bar to find songs or users</h3>
                        <?php } ?>
                        <?php if (isset($_GET["keyword"]) && $_GET["keyword"] != "") { ?>
                            <a href="search-songs?keyword=<?php echo $keyword_from_search ?>" style="color:black;text-decoration:none"><h3>Search tracks</h3></a>
                        <?php } 
                        $songList = array();
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <iframe style="display:none;" name="target"></iframe>
                            <table>
                                <tbody>
                                    <?php  
                                        foreach ($results->tracks->items as $track) {
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


                                    ?>
                                    <tr>
                                        <td class="clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
                                            <img src="<?php echo $cover; ?>" class="img-responsive" height="50"  />
                                        </td>
                                        <td class="font-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $trackName; ?></td>
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
                                    <?php } 
                                        if(!isset($_SESSION)){
                                            session_start();
                                        }
                                        unset($_SESSION['songlist']);
                                        $_SESSION['songlist'] = $songList;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="top-text text-left">
                        <?php if (isset($_GET["keyword"]) && $_GET["keyword"] != "") { ?>
                            <a href="search-artists?keyword=<?php echo $keyword_from_search ?>" style="color:black;text-decoration:none"><h3>Search artists</h3></a>
                        <?php } 
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <iframe style="display:none;" name="target"></iframe>
                            <table>
                                <tbody>
                                    <?php  
                                        foreach ($results->artists->items as $artist) {
                                            $artistName = $artist->name;
                                            $spotifyID = $artist->id;
                                            $image = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
                                            if(isset($artist->images[0]->url)){
                                                $image = $artist->images[0]->url;
                                            }
                                            $artistGenre = "Unknown Genre";
                                            if(isset($artist->genres[0])){
                                                $artistGenre = ucfirst($artist->genres[0]);
                                            }


                                    ?>
                                    <tr>
                                        <td class="clickable-row" data-href="artist?id=<?php echo $spotifyID; ?>">
                                            <img src="<?php echo $image; ?>" class="img-responsive" height="50"  />
                                        </td>
                                        <td class="font-20 clickable-row" data-href="artist?id=<?php echo $spotifyID; ?>"><?php if(strlen($artistName)<35){echo mb_substr($artistName,0,35,'utf-8');}else{ echo mb_substr($artistName,0,32,'utf-8') . "..."; } ?></td>
                                        <td class="font-20 clickable-row" data-href="artist?id=<?php echo $spotifyID; ?>"><?php echo $artistGenre; ?></td>
                                        <td>
                                            <a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>"><img src="images/spotify.svg" class="img-responsive" height="30"  /></a>
                                        </td>
                                    </tr>
                                    <?php } 
                                        if(!isset($_SESSION)){
                                            session_start();
                                        }
                                        unset($_SESSION['songlist']);
                                        $_SESSION['songlist'] = $songList;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="top-text text-left">
                        <?php if (isset($_GET["keyword"]) && $_GET["keyword"] != "") { ?>
                            <a href="search-albums?keyword=<?php echo $keyword_from_search ?>" style="color:black;text-decoration:none"><h3>Search albums</h3></a>
                        <?php } 
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <iframe style="display:none;" name="target"></iframe>
                            <table>
                                <tbody>
                                    <?php  
                                        foreach ($results->albums->items as $album) {
                                            $albumName = $album->name;
                                            $spotifyID = $album->id;
                                            $image = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
                                            if(isset($album->images[0]->url)){
                                                $image = $album->images[0]->url;
                                            }
                                            $artist = $album->artists[0]->name;


                                    ?>
                                    <tr>
                                        <td class="clickable-row" data-href="album?id=<?php echo $spotifyID; ?>">
                                            <img src="<?php echo $image; ?>" class="img-responsive" height="50"  />
                                        </td>
                                        <td class="font-20 clickable-row" data-href="album?id=<?php echo $spotifyID; ?>"><?php echo $albumName; ?></td>
                                        <td class="font-20 clickable-row" data-href="album?id=<?php echo $spotifyID; ?>"><?php echo $artist; ?></td>
                                        <td>
                                            <a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>"><img src="images/spotify.svg" class="img-responsive" height="30"  /></a>
                                        </td>
                                    </tr>
                                    <?php } 
                                        if(!isset($_SESSION)){
                                            session_start();
                                        }
                                        unset($_SESSION['songlist']);
                                        $_SESSION['songlist'] = $songList;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="top-text text-left">
                        <?php if (isset($_GET["keyword"]) && $_GET["keyword"] != "") { ?>
                            <a href="search-users?keyword=<?php echo $keyword_from_search ?>" style="color:black;text-decoration:none"><h3>Search users</h3></a>
                        <?php } 
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table>
                                <tbody>
                                    <?php  
                                        $result = $usersqli->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $name = $row["first_name"] . " " . $row["last_name"];
                                                $rated_songs = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $row["userID"])->num_rows;
                                    ?>
                                   
                                    <tr>
                                        <td class="clickable-row" data-href="profile?id=<?php echo $row["userID"]; ?>">
                                            <img src="<?php echo $row["user_image"]; ?>" class="img-responsive" height="50"  />
                                        </td>
                                        <td class="clickable-row font-20" data-href="profile?id=<?php echo $row["userID"]; ?>" >    <?php echo $row["user_name"]; ?>
                                            <?php if ($row['user_level'] == 1) {
                                                echo '<span style="color:#3075db">&#9679;</span>';
                                            }
                                            if ($row['user_level'] == 3) {
                                                echo '<span style="color:#fa4895">&#9679;</span>';
                                            }
                                            if ($row['user_level'] >= 2) {
                                                echo '<span style="color:#29cc70">&#9679;</span>';
                                            } ?>
                                        </td>
                                        <td class="clickable-row font-20" data-href="profile?id=<?php echo $row["userID"]; ?>">
                                            <?php echo $name; ?>
                                        </td>
                                        <td>
                                            <strong># songs rated: <?php echo $rated_songs; ?></strong>
                                        </td>
                                        <td>
                                            <?php if (isset($_SESSION["userID"]) && !($_SESSION["userID"] == $row["userID"])) { 
                                                if ($usersqli->query("SELECT follower_id FROM follows WHERE follower_id=".$_SESSION['userID']." AND following_id=".$row["userID"])->num_rows > 0) {
                                                ?>
                                                    <button id="follow-button-<?php echo $row["userID"]; ?>" style="color: #fff;padding:2px 10px" class="btn btn--primary" data-follow="<?php echo $row["userID"]; ?>" data-user="<?php echo $_SESSION["userID"] ?>">Unfollow</button>
												<?php } else { ?>
													<button id="follow-button-<?php echo $row["userID"]; ?>" style="color: #fff;padding:2px 10px" class="btn btn--primary" data-follow="<?php echo $row["userID"]; ?>" data-user="<?php echo $_SESSION["userID"] ?>">Follow</button>
                                                <?php } ?>
                                            <?php } else if (isset($_SESSION["userID"]) && ($_SESSION["userID"] == $row["userID"])) { ?>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn--primary" disabled="true" >Sign in to Follow</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    
                                    <?php } 
                                        } else { ?>
                                        <tr>0 results</tr>
                                    <?php } 
                                    $usersqli->close();
                                    ?>
                                   
                                </tbody>
                            </table>   
                        </div>
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
            <iframe style="display:none" name="target"></iframe>
        </div>
        <?php
            include "footer.php";
            include "script.php";
        ?>
		<script>
		$('.btn').on('click', function(){
			var buttonId = $(this).attr('id');
			var button = document.getElementById(buttonId);
			if(button.innerHTML == 'Follow'){
				$.ajax({
					type: "POST",
					url: "follow_user.php",
					data: { 'followed':button.getAttribute('data-follow'), 'follower':button.getAttribute('data-user') }
				}).done(function(){
					console.log('user followed');
					button.innerHTML = 'Unfollow';
				});
			}
			if(button.innerHTML == 'Unfollow'){
				$.ajax({
					type: "POST",
					url: "unfollow_user.php",
					data: { 'unfollowing':button.getAttribute('data-follow'), 'user':button.getAttribute('data-user') }
				}).done(function(){
					console.log('user unfollowed');
					button.innerHTML = 'Follow';
				});
			}
		});
		</script>
    </body>
</html>