<!DOCTYPE html>
<html lang="en">
<head>
	<?php include "head.php"; ?>
	<style>
	.track-content{
		width: 320px;
		height: 60px;
		border-bottom: 1px solid black;
		overflow: hidden;
	}
	.track-content .cover-img{
		width: 50px;
		height: 50px;
		position: relative;
		left: 10px;
		top: 5px;
	}
	.trackname{
		position: relative;
		left: 65px;
		top: -60px;
		width: 280px;
	}
	.artistname{
		position: relative;
		font-style: italic;
		left: 65px;
		top: -84px;
	}
	</style>
</head>
<body class="">
	<?php
	if(!isset($_SESSION)){
		session_start();
	}

	if(isset($_SESSION['queue_list']) && count($_SESSION['queue_list']) > 0){
		$queue = $_SESSION['queue_list'];
		include "api_connect.php";
		$tracks = $api->getTracks($queue)->tracks;
		$numTracks = count($tracks);

		$trackNum = 0;

		foreach($tracks as $track){
			$trackNum++;
			$trackName = $track->name;
			$artistName = $track->artists[0]->name;
			$cover = $track->album->images[0]->url;
			?>
			<div class="track-content">
				<img src="<?php echo $cover ?>" class="cover-img"/>
				<p class="trackname"><?php echo $trackName ?></p>
				<p class="artistname"><?php echo $artistName ?></p>
			</div>
			<?php
			if($trackNum >= 8){
				break;
			}
		}
		if($numTracks > 8){
			echo "+ " . ($numTracks-8) . " more tracks";
		}
		
	}else{
		echo "<h4>Queue is empty!</h4>";
	}

	?>
</body>
</html>