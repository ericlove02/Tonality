<?php
if(!isset($_SESSION)){
	session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
<style>

.toggleplay {
	position: absolute;
	left: 148px;
	top: 135px;
	width: 44px;
	height: 44px;
}
.skipback {
	position: absolute;
	left: 116px;
	top: 143px;
	width: 28px;
	height: 28px;
}
.skipforward {
	position: absolute;
	left: 196px;
	top: 143px;
	width: 28px;
	height: 28px;
}
.vote-icon{
	width: 28px !important;
	height:14px !important;
	position: absolute;
}
.rating-icon{
	width: 22px !important;
	height: 28px !important;
	position: absolute;
	left: 11px;
	top: 30px;
}
.up-vote{
	top: 10px;
	left: 10px;
}
.down-vote{
	top: 80px;
	left: 10px;
}
.coverImg{
	position: absolute;
	width: 90px;
	height: 90px;
	top: 10px;
	left: 50px;
	border-radius: 4px;
}
strong{
	position: absolute;
	left: 10px;
	top: 57px;
}
.songname{
	position: absolute;
	left: 150px;
	top: 15px;
	color: black;
}
.artistname{
	position: absolute;
	left: 150px;
	top: 50px;
	color: black;
	font-style: italic;
}
.timebar{
	position: absolute;
	width: 300px;
	left: 20px;
	top: 118px;
}
.current-time{
	position: absolute;
	top: 128px;
	left: 10px;
}
.duration{
	position: absolute;
	top: 128px;
	right: 10px;
}
.upgradeMessage {
	position: absolute;
	left: 12px;
	top: 140px;
}


.songname:hover, 
.artistname:hover{
	text-decoration: underline;
}
svg {
	color: black;
}
svg:hover{
	color:red;
	cursor:pointer;
}
.premiumLink{
	color:green;
	text-decoration: none;
}
.premiumLink:hover{
	text-decoration: underline;
}
</style>
<script type="text/javascript">
function setPlayButton(){
	document.getElementById('pause-button').style = "display:none";
	document.getElementById('play-button').style = "";
}
function setPauseButton(){
	document.getElementById('pause-button').style = "";
	document.getElementById('play-button').style = "display:none";
}
function gasVoteClicked(songId){
	if(document.getElementById("gasIcon"+songId).src.includes("/images/up-vote.png") && document.getElementById("trashIcon"+songId).src.includes("/images/down-vote.png")){
		document.getElementById("gasIcon"+songId).src = "/images/up-voted.png";
	}else if(document.getElementById("gasIcon"+songId).src.includes("images/up-voted.png") && document.getElementById("trashIcon"+songId).src.includes("images/down-vote.png")){
		document.getElementById("gasIcon"+songId).src = "images/up-vote.png";
	}else if(document.getElementById("gasIcon"+songId).src.includes("images/up-vote.png") && document.getElementById("trashIcon"+songId).src.includes("images/down-voted.png")){
		document.getElementById("gasIcon"+songId).src = "images/up-voted.png";
		document.getElementById("trashIcon"+songId).src = "images/down-vote.png";
	}
}
function trashVoteClicked(songId){
	if(document.getElementById("trashIcon"+songId).src.includes("/images/down-vote.png") && document.getElementById("gasIcon"+songId).src.includes("/images/up-vote.png")){
		document.getElementById("trashIcon"+songId).src = "/images/down-voted.png";
	}else if(document.getElementById("trashIcon"+songId).src.includes("images/down-voted.png") && document.getElementById("gasIcon"+songId).src.includes("images/up-vote.png")){
		document.getElementById("trashIcon"+songId).src = "images/down-vote.png";
	}else if(document.getElementById("trashIcon"+songId).src.includes("images/down-vote.png") && document.getElementById("gasIcon"+songId).src.includes("images/up-voted.png")){
		document.getElementById("trashIcon"+songId).src = "images/down-voted.png";
		document.getElementById("gasIcon"+songId).src = "images/up-vote.png";
	}
}

function reload(){
	setTimeout(() => { location.reload();console.log("reloaded"); }, 500);
	
}

</script>
<?php

//unset($_SESSION['token']);
if(isset($_SESSION['token'])){
	require 'vendor/autoload.php';
	include "db_songs_connect.php";
	require_once "rating_system.php";
	$api = new SpotifyWebAPI\SpotifyWebAPI();
	$api->setAccessToken($_SESSION['token']);
	try{
		$device = $api->getMyDevices();
	}catch(SpotifyWebAPI\SpotifyWebAPIException $e){
		$session = new SpotifyWebAPI\Session(
			'47a84b81d75249a2b758130418a98d76', 
			'f35285395b0a4d28a18e99ae1e44ed1f', 
			'http://gotonality.com/callback.php'
		);
		$session->refreshAccessToken($_SESSION['refresh_token']);
		$accessToken = $session->getAccessToken();
		$_SESSION['token'] = $accessToken;
		$api->setAccessToken($accessToken);
	}
	
	if(isset($api->getMyDevices()->devices[0])){
		$device = $api->getMyDevices()->devices[0]->id;
	}else{
		echo "No device connected. Open Spotify on your device,<br> you use the <a href='https://open.spotify.com/' target='_blank'>web player here</a>!<br>";
	}
	$_SESSION['playback_device'] = $device;
	$_SESSION['spotify_connected'] = True;

	$playbackInfo = $api->getMyCurrentPlaybackInfo();

	$user = $api->me();
	if(isset($playbackInfo->is_playing)){
		$isAd = ($playbackInfo->currently_playing_type == 'ad' ? True : False);
		
		// $isPremium = (isset($user->product) ? True : False);
		// premium try statement moved to signedin_spotify.php
		$isPremium = $_SESSION['isPremium'];
		//print_r($user);
		if(!$isAd){
			$isPlaying = $playbackInfo->is_playing;
			$trackId = $playbackInfo->item->id;
			$result = $mysqli->query("SELECT songID FROM ratings WHERE spotify_uri='" . $trackId . "'");
			if ($result->num_rows < 1) {
				$mysqli->query("INSERT INTO `ratings`(`songID`, `spotify_uri`, `song_pos_ratings`, `song_total_ratings`) VALUES (NULL, '" . $trackId . "', 0, 0)");
			}
			$result = $mysqli->query("SELECT songID, song_pos_ratings, song_total_ratings FROM ratings WHERE spotify_uri='$trackId'");
			$row = $result->fetch_assoc();
			$songID = $row['songID'];
			$posRating = $row["song_pos_ratings"];
			$totRatings = $row["song_total_ratings"];
			$pop = $playbackInfo->item->popularity;
			$artistId = $playbackInfo->item->artists[0]->id;
			$albumId = $playbackInfo->item->album->id;
			$coverImage = $playbackInfo->item->album->images[0]->url;
			$trackName = $playbackInfo->item->name;
			$artistName = $playbackInfo->item->artists[0]->name;
			$duration = $playbackInfo->item->duration_ms;
			$currentTimestamp = $playbackInfo->progress_ms;
			
			if(isset($_SESSION['queue_list']) && count($_SESSION['queue_list']) > 0 && $_SESSION['queue_list'][0] == $trackId){
				array_shift($_SESSION['queue_list']);
			}
			
		if (isset($_SESSION["userID"])) {
			include "db_userdata_connect.php";
			$voterTrashed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=False AND song_id = $songID")->num_rows > 0;
			$voterGassed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=True AND song_id = $songID")->num_rows > 0;
			if ($voterGassed) {
				echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"invis\"><img src=\"images/up-voted.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"vote-icon up-vote\"/></a>";
			}
			else {
				echo "<a href=\"add_rating.php?rating=gas&song=" . $songID . "\" target=\"invis\"><img src=\"images/up-vote.png\" id=\"gasIcon" . $songID . "\" onclick=\"gasVoteClicked(" . $songID . ")\" class=\"vote-icon up-vote\"/></a>";
			}

			if (get_rating($posRating, $totRatings, $pop) >= 60) {
				echo "<img src=\"images/gas-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
			}
			elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
				echo "<img src=\"images/trash-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
			}

			if ($voterTrashed) {
				echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"invis\"><img src=\"images/down-voted.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"vote-icon down-vote\"/></a>";
			}
			else {
				echo "<a href=\"add_rating.php?rating=trash&song=" . $songID . "\" target=\"invis\"><img src=\"images/down-vote.png\" id=\"trashIcon" . $songID . "\" onclick=\"trashVoteClicked(" . $songID . ")\" class=\"vote-icon down-vote\"/></a>";
			}
			echo "</div>";
		}
		else {
			echo "<div class=\"need-signin\"><a href=\"login\" target=\"_parent\"><img src=\"images/up-novote.png\" class=\"vote-icon up-vote\"/></a>";
			if (get_rating($posRating, $totRatings, $pop) >= 60) {
				echo "<img src=\"images/gas-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
			}
			elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
				echo "<img src=\"images/trash-icon.png\" class=\"rating-icon\"/><strong>" . get_rating($posRating, $totRatings, $pop) . "%</strong>";
			}
			echo "<a href=\"login\" target=\"_parent\"><img src=\"images/down-novote.png\" class=\"vote-icon down-vote\"/></a></div>";	
		}
		?>
		<a href="album?id=<?php echo $albumId ?>" target="_parent"><img src="<?php echo $coverImage ?>" class="coverImg"/></a>
		<a href="song?id=<?php echo $trackId ?>" target="_parent"><p class="songname" style="margin-top:6px;"><?php if(strlen($trackName)<37){echo mb_substr($trackName,0,50,'utf-8');}else{ echo mb_substr($trackName,0,47,'utf-8') . "..."; } ?></p></a>
		<a href="artist?id=<?php echo $artistId ?>" target="_parent"><p class="artistname" style="margin-top:6px;"><?php echo $artistName; ?></p></a>
		
		<p id="currenttime" style="display:none"><?php echo $currentTimestamp ?></p>
		<p id="songid" style="display:none"><?php echo $trackId ?></p>
		<progress class="timebar" id="timebar" value="<?php echo $currentTimestamp ?>" max="<?php echo $duration ?>"></progress>
		<p class="current-time" id="playback-time"><?php 
		$timestamp = date("i:s", $currentTimestamp / 1000); 
		$minutes = substr($timestamp, 0, strpos($timestamp, ':'));
		if($minutes == "00"){
			$minutes = "0";
		}
		else if($minutes < 10){
			$minutes = substr($minutes, 1, 1);
		}
		$seconds = substr($timestamp, strpos($timestamp, ':')+1, 2);
		echo $minutes . ":" . $seconds;
		?></p>
		<p class="duration" id="endtimeformatted"><?php 
		$timestamp = date("i:s", $duration / 1000);
		$minutes = substr($timestamp, 0, strpos($timestamp, ':'));
		if($minutes == "00"){
			$minutes = "0";
		}
		else if($minutes < 10){
			$minutes = substr($minutes, 1, 1);
		}
		$seconds = substr($timestamp, strpos($timestamp, ':')+1, 2);
		echo $minutes . ":" . $seconds;	?></p>
		<?php
		
			 } else{
			echo "Currently Playing Ad";
		}
		if($isPremium){ // FIXME BEFORE UPLOAD?>
	<!--	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
	  <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
	</svg> -->
		<?php if(isset($isPlaying) && $isPlaying){?> 

		<br>
		<svg id="pause-button" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="toggleplay bi bi-pause" viewBox="0 0 16 16">
	  <path d="M6 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5z"/>
	</svg>

		<br>
		<svg id="play-button" style="display:none" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="toggleplay bi bi-play" viewBox="0 0 16 16">
	  <path d="M10.804 8L5 4.633v6.734L10.804 8zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696l6.363 3.692z"/>
	</svg>
	<script type="text/javascript">
	function updatePlaybackTime(){
		var time = document.getElementById("playback-time").innerHTML;
		var colonIndex = time.indexOf(':');
		var minutes = parseInt(time.substring(0, colonIndex));
		var seconds = parseInt(time.substring(colonIndex+1));

		if(seconds == 59){
			minutes += 1;
			seconds = 00;
		}else{
			seconds += 1;
		}
		if(seconds <= 9){
			seconds = "0" + seconds;
		}
		document.getElementById("playback-time").innerHTML = minutes + ":" + seconds;
		
		// reload if song is at end
		if(document.getElementById('playback-time').innerHTML == document.getElementById('endtimeformatted').innerHTML){

			//setTimeout(() => { location.reload();console.log("reloaded"); }, 10);
			location.reload();console.log("reloaded");
		}
	}
	function updateTimebar(){
		var time = document.getElementById("currenttime").innerHTML;
		time = parseInt(time) + 50;
		document.getElementById("currenttime").innerHTML = time;
		document.getElementById("timebar").value = time;
	}
	setInterval(updateTimebar, 50);
	setInterval(updatePlaybackTime, 1000);
	</script>
		<?php }else{ ?>
			<br>
		<svg id="pause-button" style="display:none" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="toggleplay bi bi-pause" viewBox="0 0 16 16">
	  <path d="M6 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5z"/>
	</svg>
	
		<br>
		<svg id="play-button" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="toggleplay bi bi-play" viewBox="0 0 16 16">
	  <path d="M10.804 8L5 4.633v6.734L10.804 8zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696l6.363 3.692z"/>
	</svg>
	
		<?php } ?>
		<svg id="prev-button" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="skipback bi bi-skip-backward" viewBox="0 0 16 16">
			<path d="M.5 3.5A.5.5 0 0 1 1 4v3.248l6.267-3.636c.52-.302 1.233.043 1.233.696v2.94l6.267-3.636c.52-.302 1.233.043 1.233.696v7.384c0 .653-.713.998-1.233.696L8.5 8.752v2.94c0 .653-.713.998-1.233.696L1 8.752V12a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5zm7 1.133L1.696 8 7.5 11.367V4.633zm7.5 0L9.196 8 15 11.367V4.633z"/>
		</svg>
		<svg id="next-button" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="skipforward bi bi-skip-forward" viewBox="0 0 16 16">
		  <path d="M15.5 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V8.752l-6.267 3.636c-.52.302-1.233-.043-1.233-.696v-2.94l-6.267 3.636C.713 12.69 0 12.345 0 11.692V4.308c0-.653.713-.998 1.233-.696L7.5 7.248v-2.94c0-.653.713-.998 1.233-.696L15 7.248V4a.5.5 0 0 1 .5-.5zM1 4.633v6.734L6.804 8 1 4.633zm7.5 0v6.734L14.304 8 8.5 4.633z"/>
		</svg>
		<?php } else{
			 if(isset($isPlaying) && $isPlaying){?>
				 <script type="text/javascript">
				function updatePlaybackTime(){
					var time = document.getElementById("playback-time").innerHTML;
					var colonIndex = time.indexOf(':');
					var minutes = parseInt(time.substring(0, colonIndex));
					var seconds = parseInt(time.substring(colonIndex+1));

					if(seconds == 59){
						minutes += 1;
						seconds = 00;
					}else{
						seconds += 1;
					}
					if(seconds <= 9){
						seconds = "0" + seconds;
					}
					document.getElementById("playback-time").innerHTML = minutes + ":" + seconds;
					
					// reload if song is at end
					if(document.getElementById('playback-time').innerHTML == document.getElementById('endtimeformatted').innerHTML){
						setTimeout(() => { location.reload();console.log("reloaded"); }, 1500);
					}
				}
				function updateTimebar(){
					var time = document.getElementById("currenttime").innerHTML;
					time = parseInt(time) + 50;
					document.getElementById("currenttime").innerHTML = time;
					document.getElementById("timebar").value = time;
				}
				setInterval(updateTimebar, 50);
				setInterval(updatePlaybackTime, 1000);
				</script>
			 <?php }
			echo "<br><p class='upgradeMessage'>Upgrade to <a href='https://www.spotify.com/us/premium/' target='_blank' class='premiumLink'>Spotfiy Premium</a> to get full functions</p>";
		}
		?>	
		<iframe name='invis' style="display:none"></iframe>
		<?php
	}else{
		echo "Open Spotify and start playing music to get started!";
	}
		
}else{?>
	We're getting things ready!<br>
	Wait a couple seconds, if playback doesn't start,
	<a href='unset_access_token.php' target='_parent'>Click Here</a>
	<script>
	setTimeout(reload, 2000);
	</script>
<?php
}
?>
<script>
$('#next-button').click(function(){
	$.ajax({
		type: "POST",
		url: "playbackFunctions/next.php",
		success: function(){console.log('successfully sent action');}
	}).done(function(){
		clearInterval(refresh);
		refresh = setInterval(checkForRefresh, 2000);
		console.log('next performed');
		reload();
	});
});
$('#prev-button').click(function(){
	$.ajax({
		type: "POST",
		url: "playbackFunctions/previous.php",
		success: function(){console.log('successfully sent action');}
	}).done(function(){
		clearInterval(refresh);
		refresh = setInterval(checkForRefresh, 2000);
		console.log('prev performed');
		reload();
	});
});
$('#pause-button').click(function(){
	$.ajax({
		type: "POST",
		url: "playbackFunctions/pause.php",
		success: function(){console.log('successfully sent action');}
	}).done(function(){
		clearInterval(refresh);
		refresh = setInterval(checkForRefresh, 2000);
		console.log('pause performed');
		setPlayButton();
		reload();
	});
});
$('#play-button').click(function(){
	$.ajax({
		type: "POST",
		url: "playbackFunctions/play.php",
		data: { 'pos': document.getElementById('currenttime').innerHTML, 'id':document.getElementById('songid').innerHTML },
		success: function(){console.log('successfully sent action');}
	}).done(function(){
		clearInterval(refresh);
		refresh = setInterval(checkForRefresh, 2000);
		console.log('play performed');
		setPauseButton();
		reload();
	});
});

function checkForRefresh(){
	$.ajax({
		url:'playbackFunctions/checkForRefresh.php',
		type: 'POST',
		data: {'isPlaying':<?php echo "\"" . $isPlaying . "\"" ?>, 'id':<?php echo "\"" . $trackId . "\"" ?>},
		success: function(needRefresh){
					console.log('successfully check for refresh');
					if(needRefresh){
						console.log('reload');
						reload();
					}
				}
	});
}
refresh = setInterval(checkForRefresh, 2000);

</script>

</body>
</html>