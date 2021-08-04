<?php
if(!isset($_SESSION)){
	session_start();
}

require_once "rating_system.php";
include "api_connect.php";
include "db_songs_connect.php";
include "db_userdata_connect.php";

$gassedListResults = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_POST["profile-id"] . " AND vote_type=True ORDER BY voted_at DESC LIMIT ". $_POST['start_num'].", 50");

$gassedList = array();
while($row = $gassedListResults->fetch_assoc()){
	array_push($gassedList, $row['song_id']);
}
$index = 0;
 
$songs = array();
foreach ($gassedList as $songID) {
	$results = $mysqli->query("SELECT spotify_uri, song_pos_ratings, song_total_ratings FROM ratings WHERE songID=$songID");
	$row = $results->fetch_assoc();
	array_push($songs, $row['spotify_uri']);
	$spotifyIDs[] = $row['spotify_uri'];
	$songIDs[] = trim($songID);
	$posRatings[] = $row["song_pos_ratings"];
	$totRatings[] = $row["song_total_ratings"];
}
$tracks = $api->getTracks($songs);

	foreach ($tracks->tracks as $track) {
	$spotifyID = $spotifyIDs[$index];
	$trackName = $track->name;
	$trackArtist = $track->artists[0]->name;
	$songID = $songIDs[$index];
	$posRating = $posRatings[$index];
	$totRating = $totRatings[$index];
	$pop = $track->popularity;
	$cover = $track->album->images[0]->url;

	$index++;
?>
	<tr class="<?php if(($index+1)%40 == 0){ echo " gassed-trigger"; } ?>">
		<td class="clickable-row" data-href="song?id=<?php echo $spotifyID; ?>">
			<img src="<?php echo $cover; ?>" class="img-responsive" height="50"  />
		</td>
		<td class="font-15 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php if(strlen($trackName)<15){echo mb_substr($trackName,0,15,'utf-8');}else{ echo mb_substr($trackName,0,12,'utf-8') . "..."; } ?></td>
		<td class="font-15 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php if(strlen($trackArtist)<13){echo mb_substr($trackArtist,0,13,'utf-8');}else{ echo mb_substr($trackArtist,0,10,'utf-8') . "..."; } ?></td>
		<td>
			<a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>"><img src="images/spotify.svg" class="img-responsive" height="30"  /></a>
		</td>
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
		<td class="font-td" style='white-space:nowrap'>
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

					if (get_rating($posRating, $totRating, $pop) >= 60) {
						echo "<img src=\"images/gas-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRating, $pop) . "%</strong>";
					}
					elseif (get_rating($posRating, $totRating, $pop) <= 60) {
						echo "<img src=\"images/trash-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRating, $pop) . "%</strong>";
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
					if (get_rating($posRating, $totRating, $pop) >= 60) {
						echo '<a data-tooltip="Signin"><img data-tooltip="Signin" src="images/gas-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRating, $pop).'%';
					}
					elseif (get_rating($posRating, $totRating, $pop) <= 60) {
						echo '<a data-tooltip="Signin"><img src="images/trash-icon.png" class="img-responsive" height="30"  /></a>'.get_rating($posRating, $totRating, $pop).'%';
					}
					echo '<a href="login" data-tooltip="Signin"><i class="fa fa-chevron-down"></i></a>';
				}
			?>
		</td>
	</tr>
<?php } ?>
<tr class="more-gassed-content">
</tr>
