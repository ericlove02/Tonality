<?php
if(!isset($_SESSION)){
	session_start();
}

require_once "rating_system.php";
include "db_songs_connect.php";
include "api_connect.php";
include "db_userdata_connect.php";

include 'Mobile_Detect.php';
$detect = new Mobile_Detect();

$isMobile = $detect->isMobile();


if(isset($_SESSION["userID"]) && $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " ORDER BY voted_at DESC")->num_rows >= 1){

	$gassedSongs = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=1 ORDER BY voted_at DESC LIMIT 5");
	$songs = array();
	while($row = $gassedSongs->fetch_assoc()){
		$songID = $row['song_id'];
		$spotifyID = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . trim($songID))->fetch_assoc() ['spotify_uri'];
		array_push($songs, $spotifyID);
	}
	$results = $api->getRecommendations(['seed_tracks' => $songs, 'limit' => 50]);
	
}else{
	$results =$api->getRecommendations(['seed_tracks' => $api->getPlaylistTracks('spotify:playlist:37i9dQZF1DXcBWIGoYBM5M', ['limit' => 1])->items[0]->track->id, 'limit'=>50]);
}
$songList = array();
$index = 1;
?>
<?php  
foreach ($results->tracks as $track) {
	$spotifyID = $track->id;
	array_push($songList, $spotifyID);
	$trackName = $track->name;
	$trackArtist = $track->artists[0]->name;
	$cover = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
	if(isset($track->album->images[0]->url)){
		$cover = $track->album->images[0]->url;
	}
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
	
	$artistID = $track->artists[0]->id;
	$albumID = $track->album->id;

	include "db_userdata_connect.php";
	$voterTrashed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=False AND song_id = $songID")->num_rows > 0;
	$voterGassed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=True AND song_id = $songID")->num_rows > 0;
	$usersqli->close();
	$votedOn = ($voterTrashed || $voterGassed);
	if($votedOn){
		continue;
	}
	if($index == 1 || $index == 10 || $index == 20 || $index == 30 || $index == 40){ ?>
<!--------- IN FEED AD ------------>
							<div class="<?php if(!$isMobile){ echo "boxed boxed--border"; } ?> feed-box" style="<?php if($isMobile){ echo "width:100vw;margin-left:-15px;border-bottom:1px solid #ececec;border-top:1px solid #ececec;margin-bottom:20px;"; } ?>">
								<div class="col-lg-12 col-md-12 col-sm-12 feed-box" style="display:inline-block;margin-top:-12px">
									<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
									<!-- Square Ad -->
									<ins class="adsbygoogle"
										 style="display:block"
										 data-ad-client="ca-pub-3114841394059389"
										 data-ad-slot="3260674656"
										 data-ad-format="auto"
										 data-full-width-responsive="true"></ins>
									<script>
										 (adsbygoogle = window.adsbygoogle || []).push({});
									</script>
								</div>
								<div class="col-lg-9 col-md-9 col-9 pd-0-15" style="display:inline-block;">
									<h4 style="margin-top:-24px">Sponsored</h4>
								</div>
								
							</div>
<!------ END IN FEED AD ------------>	
	<?php
	}
?>

<div class="<?php if(!$isMobile){ echo "boxed boxed--border"; } ?> feed-box <?php if($index == 10){ echo "recommend-trigger"; } ?>" style="<?php if($isMobile){ echo "width:100vw;margin-left:-15px;"; } ?>">		
		<div class="col-lg-12 col-md-12 col-sm-12 feed-box" style="display:inline-block;margin-top:-12px">
			<a href="album?id=<?php echo $albumID ?>"><img class="width100" src="<?php echo $cover ?>" /></a>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6" style="display:inline-block;">
			<div class="text-center" id="icon-box">
			<?php
				if (isset($_SESSION["userID"])) {
					include "db_userdata_connect.php";
					$voterTrashed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=False AND song_id = $songID")->num_rows > 0;
					$voterGassed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=True AND song_id = $songID")->num_rows > 0;
					if ($voterGassed) {
						echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"target\"><img src=\"images/up-voted.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"home-img-1\" style='width:50%;margin-bottom:50px' /></a>";
					}
					else {
						echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"target\"><img src=\"images/up-vote.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"home-img-1\" style='width:50%;margin-bottom:50px'/></a>";
					}

					if (get_rating($posRating, $totRatings, $pop) >= 60) {
						echo "<img src=\"images/gas-icon.png\" class=\"home-img-2\" style='width:30%;margin-bottom:8px;'/><strong style='font-size:28px' class='img-percent'>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
					}
					elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
						echo "<img src=\"images/trash-icon.png\" class=\"home-img-2\" style='width:30%;margin-bottom:8px;' /><strong class='img-percent' style='font-size:28px'>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
					}

					if ($voterTrashed) {
						echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"target\"><img src=\"images/down-voted.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"home-img-1\" style='width:50%;margin-top:50px' /></a>";
					}
					else {
						echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"target\"><img src=\"images/down-vote.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"home-img-1\" style='width:50%;margin-top:50px' /></a>";
					}
				} else {
				   
					echo '<a data-tooltip="Signin" href="login"><img src="images/up-novote.png" class="home-img-1" style="width:50%;margin-bottom:50px" /></a>';
							
					if (get_rating($posRating, $totRatings, $pop) >= 60) {
						echo "<img src=\"images/gas-icon.png\" class=\"home-img-2\" style='width:30%;margin-bottom:8px;'/><strong style='font-size:28px' class='img-percent'>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
					}
					elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
						echo "<img src=\"images/trash-icon.png\" class=\"home-img-2\" style='width:30%;margin-bottom:8px;' /><strong class='img-percent' style='font-size:28px'>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
					}
					
					echo '<a href="login" data-tooltip="Signin"><img src="images/down-novote.png" class="home-img-1" style="width:50%;margin-bottom:50px" /></a>';
					
				}
			?>
			</div>
		</div>
		
		<div class="col-lg-9 col-md-9 col-9 pd-0-15" style="display:inline-block;">
		<a href="song?id=<?php echo $spotifyID ?>"><h2><?php echo $trackName ?></h2></a>
		<a href="artist?id=<?php echo $artistID ?>"><h4 style="margin-top:-24px"><i><?php echo $trackArtist ?></i></h4></a>
		</div>
		<div class="col-lg-2 col-md-2 col-2 text-right" style="display:inline-block;white-space:nowrap;">
			<?php
			if(isset($_SESSION['spotify_connected']) && $_SESSION['spotify_connected'] && $_SESSION['isPremium']){
				echo "<td style='white-space:nowrap'><a target=\"target\" href=\"playbackFunctions/queue.php?id=".$spotifyID."\" onclick='showAlert()' style='color:black'><div class='queue-button' style='width:fit-content;display:inline-block;margin-left:8px;'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"currentColor\" class=\"bi bi-music-note\" viewBox=\"0 0 16 16\">
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
		</div>
		<div class="col-lg-12 col-md-12 col-12" style="display:inline-block">
			<div style="width:80%;margin:0 auto;">
				<iframe style="min-height: unset;" height="80px" src="https://open.spotify.com/embed/track/<?php echo $spotifyID; ?>" frameborder="0" allowtransparency="true" allow="encrypted-media" loading="lazy"></iframe>
			</div>
		</div>
		<div style="margin-bottom:40px"></div>
</div>

 <?php 
 $index++;
 }
?>
<div class="more-content">
</div>