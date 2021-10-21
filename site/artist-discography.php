<?php
require_once "rating_system.php";
include "db_songs_connect.php";
include "db_userdata_connect.php";
include "api_connect.php";
$artistID = $_GET["id"];
$rating = 6.9;
$artist = $api->getArtist($artistID);
$artistName = $artist->name;
$image = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
if(isset($artist->images[0]->url)){
	$image = $artist->images[0]->url;
}
$genre = "Unknown genre";
if(isset($artist->genres[0])){
	$genre = $artist->genres[0];
}
$results = $api->getArtistTopTracks($artistID, ['country' => "US", 'limit' => 5]);
$songNum = 1;
$songList = array();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <div class="main-container">
            <section class="bg--secondary space--sm">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="boxed boxed--lg boxed--border">
                                <div class="text-block text-center">
                                    <img alt="avatar" src="<?php echo $image; ?>" class="image--md" style="max-height: 15.428571em;" />
                                    <span class="h5"><?php echo $artistName; ?></span>
                                    <span><?php echo ucfirst($genre); ?></span><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h2>Artist Full Discography</h2>
                        </div>
                        <?php
                            $results = $api->getArtistAlbums($artistID, ['limit'=>50]);
                            $albumNames = array();
                        ?>
                        <?php foreach ($results->items as $album) { 
                            if (!in_array($album->name, $albumNames)) {
                                array_push($albumNames, $album->name);
                            }
                            else {
                                continue;
                            }
                            $albumID = $album->id;
                            $albumName = $album->name;
                            $cover = $album->images[0]->url;
                            $releaseDate = $album->release_date;
                        ?>
                        <div class="col-lg-12">
                            <div class="boxed boxed--lg boxed--border">
                                <div class="boxed boxed--border clickable-row" data-href="album?id=<?php echo $albumID; ?>">
                                    <div class="row">
                                        <div class="col-md-2 text-center">
                                            <img alt="avatar" src="<?php echo $cover; ?>" class="image--sm"/>
                                        </div>
                                        <div class="col-md-10">
                                            <h5 class="mgb-0"><?php echo $albumName; ?></h5>
                                            <h5 class="mgb-0"><?php echo $artistName; ?></h5>
                                            <h5 class="mgb-0"><?php echo $releaseDate; ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    $result = $api->getAlbumTracks($albumID);
                                    $songs = array();
                                    foreach ($result->items as $track) {
                                        array_push($songs, $track->id);
                                    }
                                    $tracks = $api->getTracks($songs);
                                ?>
                                <?php foreach ($tracks->tracks as $track) { 
                                    $trackNum = $track->track_number;
                                    $spotifyID = $track->id;
                                    array_push($songList, $spotifyID);
                                    $trackName = $track->name;
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
                                    $cover = $track
                                        ->album
                                        ->images[0]->url;
                                ?>
                                <div class="container"> 
                                    <div class="row mg-20">
                                        <div class="cover-img flex-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
                                            <span class="mgr-30"><?php echo $trackNum; ?></span>
                                            <img src="<?php echo $cover; ?>" class="img-responsive" height="50"  />
                                        </div>
                                        <div class="track-artist font-20 flex-20 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
                                            <?php echo mb_substr($trackName,0,20,'utf-8'); ?>
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
                                                    echo "<img src=\"images/gas-icon.png\" class=\"rating-icon\"/><strong class='font-20'>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
                                                }
                                                elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
                                                    echo "<img src=\"images/trash-icon.png\" class=\"margin-10 rating-icon\"/><strong class='font-20'>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
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
                            
                        </div>
                        <?php } 
                            if(!isset($_SESSION)){
                                session_start();
                            }
                            unset($_SESSION['songlist']);
                            $_SESSION['songlist'] = $songList;

                            $mysqli->close();
                            $usersqli->close();
                        ?>
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