<?php
if(!isset($_SESSION)){
	session_start();
}

require_once "rating_system.php";
include "db_songs_connect.php";
include "api_connect.php";
include "db_userdata_connect.php";

$index = 1;

/*if(isset($_SESSION["userID"]) && $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " ORDER BY voted_at DESC")->num_rows >= 1){

	$gassedSongs = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=1 ORDER BY voted_at DESC LIMIT 1");
	$songs = array();
	while($row = $gassedSongs->fetch_assoc()){
		$songID = $row['song_id'];
		$spotifyID = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . trim($songID))->fetch_assoc() ['spotify_uri'];
		array_push($songs, $spotifyID);
	}
	$result = $api->getRecommendations(['seed_tracks' => $songs, 'limit' => 1]);
	$seed = $result->tracks[0]->id;
}else{
	$seeds = $api->getPlaylistTracks('spotify:playlist:37i9dQZF1DXcBWIGoYBM5M', ['limit' => 1])->tracks[0]->track->id;;
}*/

//$topTracks = $api->getPlaylistTracks('spotify:playlist:37i9dQZF1DXcBWIGoYBM5M', ['limit' => 5]);
//$results = $api->getRecommendations(['seed_tracks'=>[$topTracks->items[0]->track->id,$topTracks->items[1]->track->id, $seed], 'limit' => 100, 'min_popularity' => 40, 'max_popularity' => 80, 'target_energy' => .4]);
$seeds = array();
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in']){
	$gassedSongs = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=1 ORDER BY voted_at DESC LIMIT 50");
	$limit = $gassedSongs->num_rows;
	$songs = array();
	while($row = $gassedSongs->fetch_assoc()){
		$songID = $row['song_id'];
		$spotifyID = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . trim($songID))->fetch_assoc() ['spotify_uri'];
		array_push($songs, $spotifyID);
	}
	array_push($seeds, $songs[rand(0,$limit-1)]);
	array_push($seeds, $songs[rand(0,$limit-1)]);
}
$topTracks = $api->getPlaylistTracks('spotify:playlist:37i9dQZF1DXcBWIGoYBM5M', ['limit' => 15]);

array_push($seeds, $topTracks->items[rand(0,14)]->track->id);
array_push($seeds, $topTracks->items[rand(0,14)]->track->id);
array_push($seeds, $topTracks->items[rand(0,14)]->track->id);

$results = $api->getRecommendations(['seed_tracks'=>$seeds, 'limit' => 100]);

if(!isset($songList)){
	$songList = array();
}
 
$song_num = 0;

foreach ($results->tracks as $track) {
	
	$spotifyID = $track->id;
	array_push($songList, $spotifyID);
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
	
	$albumID = $track->album->id;
	$artistID = $track->artists[0]->id;
	?>
	
	<div class="grid-cover <?php if($song_num == 30){ echo " explore-trigger"; } ?>" style="">
		<a class="button" onclick="modalOpen(this)" id="button-<?php echo $spotifyID ?>"><img src="<?php echo $cover ?>" /></a>
	</div>
	
	<div id="modal-<?php echo $spotifyID ?>" class="modal" style="width:90vw;display:none;position:fixed;top:10vh;left:5vw;z-index:1">
		<div id="cover-bkg-<?php echo $spotifyID ?>" onclick="modalClickedOff('<?php echo $spotifyID ?>')" style="z-index:-1;position:fixed;top:0;left:0;right:0;bottom:0;background-color:black;opacity:.75;display:none"></div>
	  <!-- Modal content -->
	  
	  <div class="boxed boxed--border" style="max-width:500px;height:fit-content;margin:auto">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			<svg xmlns="http://www.w3.org/2000/svg" onclick="modalClose(this)" class="close" id="close-<?php echo $spotifyID ?>" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
			  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
			</svg>
		</div>
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
	<div class="col-lg-12 col-md-12 col-12 text-center" style="display:inline-block">
		<div style="width:80%;margin:0 auto;">
			<div id="widget-placeholder-<?php echo $spotifyID; ?>"></div>
		</div>
	</div>
		
	
</div>
</div>
<?php 
$song_num++;
} ?>
<div class="more-content col-lg-12 col-md-12 col-12 text-center">
	<img src="images/loading.gif" style="width:50px;height:50px" />
</div>