<?php
// get content
require_once "rating_system.php";
include "db_userdata_connect.php";

if(!isset($_SESSION)){
	session_start();
}

include 'Mobile_Detect.php';
$detect = new Mobile_Detect();

$isMobile = $detect->isMobile();

function humanTiming ($time){
	$time = time() - $time;
	$tokens = array (
		31536000 => ' years ago',
		2592000 => ' months ago',
		604800 => ' weeks ago',
		86400 => ' days ago',
		3600 => ' hours ago',
		60 => ' minutes ago',
		1 => ' seconds ago'
	);

	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.$text;
	}
}
		
$content = $usersqli->query("SELECT user_votes.song_id AS song_id, NULL as content, user_votes.voted_at AS date, user_votes.vote_type AS vote_type, user_votes.vote_by AS user_id, 'vote' AS type
														FROM user_votes
														LEFT JOIN follows
														ON follows.following_id = user_votes.vote_by
														WHERE follows.follower_id = ".$_SESSION['userID']." 
														UNION
														SELECT review_on AS song_id, review_content AS content, review_date AS date, NULL as vote_type, review_by as user_id, 'review' AS type
														FROM reviews 
														LEFT JOIN follows
														ON follows.following_id = reviews.review_by
														WHERE follows.follower_id = ".$_SESSION['userID']." 
														UNION 
                                                        SELECT pin_item_id AS song_id, pin_type AS content, pinned_at AS date, NULL as vote_type, user_id as user_id, 'pin' AS type
														FROM pins 
														LEFT JOIN follows
														ON follows.following_id = pins.user_id 
														WHERE follows.follower_id = ".$_SESSION['userID']." 
														ORDER BY date DESC
														LIMIT ". $_POST['start_num'].", 50");

$spotifyIDs = [];
$usernames = [];
$userIDs = [];
$vote_types = [];
$dates = [];
$user_images = [];
$songIDs = [];
$pin_types=[];
if($content->num_rows > 0){
while($row = $content->fetch_assoc()){
	include "db_songs_connect.php";
	if($row['type'] != 'pin'){
		$spotifyIDs[] = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=".$row['song_id'])->fetch_assoc()['spotify_uri'];
		$userrow = $usersqli->query("SELECT user_name,user_image,user_level FROM data WHERE userID=".$row['user_id'])->fetch_assoc();
		$usernames[] = $userrow['user_name'];
		$userIDs[] = $row['user_id'];
		$vote_types[] = $row['vote_type'];
		$dates[] = $row['date'];
		$user_images[] = $userrow['user_image'];
		$songIDs[] = $row['song_id'];
		$levels[] = $userrow['user_level'];
		$item_types[] = $row['type'];
		$review_contents[] = $row['content'];
	}else{
		$pins_types[] = $row['content'];
		$pins_spotifyIDs[] = $row['song_id'];
		$userrow = $usersqli->query("SELECT user_name,user_image,user_level FROM data WHERE userID=".$row['user_id'])->fetch_assoc();
		$pins_usernames[] = $userrow['user_name'];
		$pins_userIDs[] = $row['user_id'];
		$dates[] = $row['date'];
		$pins_user_images[] = $userrow['user_image'];
		$pins_songIDs[] = $mysqli->query("SELECT songID FROM ratings WHERE spotify_uri='".$row['song_id']."'")->fetch_assoc()['songID'];
		$pins_levels[] = $userrow['user_level'];
		$item_types[] = 'pin';
	}
 } 
 
 include "api_connect.php";
 $tracks = $api->getTracks($spotifyIDs);
 
 $index = 0;
 $pin_index = 0;
 $songList = array();
 
 for($i = 0; $i < 50; $i++){ 
  if($item_types[$index] == 'vote' || $item_types[$index] == 'review'){
	$track = $tracks->tracks[$index];
	$songID = $songIDs[$index];
	
	$spotifyID = $spotifyIDs[$index];
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
  }
 ?>



<!--------- IN FEED AD ------------>
<?php if($i == 0 || $i == 10 || $i == 20 || $i == 30 || $i == 40){ ?>
							<div class="<?php if(!$isMobile){ echo "boxed boxed--border"; } ?> feed-box" style="<?php if($isMobile){ echo "width:100vw;margin-left:-15px;border-bottom:1px solid #ececec;border-top:1px solid #ececec;margin-bottom:10px;"; } ?>">
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
<?php } ?>
<!------ END IN FEED AD ------------>



<div class="<?php if(!$isMobile){ echo "boxed boxed--border"; } ?> feed-box <?php if($i == 3){ echo "following-trigger"; } ?>" style="<?php if($isMobile){ echo "width:100vw;margin-left:-15px;"; } ?>">
	<?php if($item_types[$i] == 'vote'){ ?>
		<?php if($vote_types[$index]){ ?>
			<div class="col-lg-12 col-md-12 col-12" style="border-top:1px solid green;border-bottom:1px solid green;padding:6px;margin-bottom:12px;background-color:#ebfff2">
				<div class="col-lg-2 col-md-2 col-2 text-right" style="display:inline-block">
					<a href="profile?id=<?php echo $userIDs[$index]; ?>"><img src="<?php echo $user_images[$index] ?>" style="width:40px;height:40px;border-radius:4px"/></a>
				</div>
				<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
					<a href="profile?id=<?php echo $userIDs[$index]; ?>" style="color:black">
						@<?php echo $usernames[$index]; ?>
					</a>
					<?php if ($levels[$index] == 1) {
						echo '<span style="color:#3075db">&#9679;</span>';
					}
					if ($levels[$index] == 3) {
						echo '<span style="color:#fa4895">&#9679;</span>';
					}
					if ($levels[$index] >= 2) {
						echo '<span style="color:#29cc70">&#9679;</span>';
					} ?>
					<span>
					</span>
				</div>
			</div>
		<?php } else { ?>
			<div class="col-lg-12 col-md-12 col-12" style="border-top:1px solid red;border-bottom:1px solid red;padding:6px;margin-bottom:12px;background-color:#ffefed">
				<div class="col-lg-2 col-md-2 col-2 text-right" style="display:inline-block">
					<a href="profile?id=<?php echo $userIDs[$index]; ?>"><img src="<?php echo $user_images[$index] ?>" style="width:40px;height:40px;border-radius:4px"/></a>
				</div>
				<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
					<a href="profile?id=<?php echo $userIDs[$index]; ?>" style="color:black">
						@<?php echo $usernames[$index]; ?>
					</a>
					<?php if ($levels[$index] == 1) {
						echo '<span style="color:#3075db">&#9679;</span>';
					}
					if ($levels[$index] == 3) {
						echo '<span style="color:#fa4895">&#9679;</span>';
					}
					if ($levels[$index] >= 2) {
						echo '<span style="color:#29cc70">&#9679;</span>';
					} ?>
					<span>
					</span>
				</div>
			</div>
		<?php } 
		?>
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
				<iframe style="min-height: unset;" height="80px" src="https://open.spotify.com/embed/track/<?php echo $spotifyID; ?>" frameborder="0" allowtransparency="true" allow="encrypted-media" loading="lazy"></iframe>
			</div>
		</div>
		<h5 class="mgb-0 h6 type--uppercase type--fade  pd-0-15"><?php echo humanTiming(strtotime($dates[$i])); ?></h5>
		<div style="margin-bottom:40px"></div>
	<?php }else if($item_types[$i] == 'review'){ ?>
		
		<div class="col-lg-12 col-md-12 col-12" style="border-top:1px solid black;border-bottom:1px solid black;padding:6px;margin-bottom:12px;background-color:#fcfcfc">
			<div class="col-lg-2 col-md-2 col-2 text-right" style="display:inline-block">
				<a href="profile?id=<?php echo $userIDs[$index]; ?>"><img src="<?php echo $user_images[$index] ?>" style="width:40px;height:40px;border-radius:4px"/></a>
			</div>
			<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
				<a href="profile?id=<?php echo $userIDs[$index]; ?>" style="color:black">
					@<?php echo $usernames[$index]; ?>
				</a>
				<?php if ($levels[$index] == 1) {
					echo '<span style="color:#3075db">&#9679;</span>';
				}
				if ($levels[$index] == 3) {
					echo '<span style="color:#fa4895">&#9679;</span>';
				}
				if ($levels[$index] >= 2) {
					echo '<span style="color:#29cc70">&#9679;</span>';
				} ?>
				<span>
				</span>
			</div>
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
		<div class="col-lg-12 col-md-12 col-12" style="display:inline-block">
			<div style="width:80%;margin:0 auto;">
				<iframe style="min-height: unset;" height="80px" src="https://open.spotify.com/embed/track/<?php echo $spotifyID; ?>" frameborder="0" allowtransparency="true" allow="encrypted-media" loading="lazy"></iframe>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-12">
			<div class="boxed boxed--border">
				<?php echo $review_contents[$index] ?>
			</div>
		</div>
		<h5 class="mgb-0 h6 type--uppercase type--fade  pd-0-15"><?php echo humanTiming(strtotime($dates[$i])); ?></h5>
		<div style="margin-bottom:40px"></div>
<?php } else if ($item_types[$i] == 'pin'){ ?>								
		<?php if($pins_types[$pin_index] == 'track'){ 
			$track = $api->getTrack($pins_spotifyIDs[$pin_index]);

			$songID = $pins_songIDs[$pin_index];
	
			$spotifyID = $pins_spotifyIDs[$pin_index];
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
		?>
		<div class="col-lg-12 col-md-12 col-12" style="border-top:1px solid #e3e3e3;border-bottom:1px solid #e3e3e3;padding:6px;margin-bottom:12px;background-color:#ebf9ff">
			<div class="col-lg-2 col-md-2 col-2 text-right" style="display:inline-block">
				<a href="profile?id=<?php echo $pins_userIDs[$pin_index]; ?>"><img src="<?php echo $pins_user_images[$pin_index] ?>" style="width:40px;height:40px;border-radius:4px"/></a>
			</div>
			<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
				<a href="profile?id=<?php echo $pins_userIDs[$pin_index]; ?>" style="color:black">
					@<?php echo $pins_usernames[$pin_index]; ?>
				</a>
				<?php if ($pins_levels[$pin_index] == 1) {
					echo '<span style="color:#3075db">&#9679;</span>';
				}
				if ($pins_levels[$pin_index] == 3) {
					echo '<span style="color:#fa4895">&#9679;</span>';
				}
				if ($pins_levels[$pin_index] >= 2) {
					echo '<span style="color:#29cc70">&#9679;</span>';
				} ?>
				<span>
				</span>
			</div>
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
		<div class="col-lg-12 col-md-12 col-12" style="display:inline-block">
			<div style="width:80%;margin:0 auto;">
				<iframe style="min-height: unset;" height="80px" src="https://open.spotify.com/embed/track/<?php echo $spotifyID; ?>" frameborder="0" allowtransparency="true" allow="encrypted-media" loading="lazy"></iframe>
			</div>
		</div>
		<h5 class="mgb-0 h6 type--uppercase type--fade  pd-0-15"><?php echo humanTiming(strtotime($dates[$i])); ?></h5>
		<div style="margin-bottom:40px"></div>
	<?php } else if($pins_types[$pin_index] == 'artist'){ 
			$artist = $api->getArtist($pins_spotifyIDs[$pin_index]);
	
			$spotifyID = $pins_spotifyIDs[$pin_index];
			$artistName = $artist->name;
			$artistGenre = ucwords($artist->genres[0]);
			$cover = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
			if(isset($artist->images[0]->url)){
				$cover = $artist->images[0]->url;
			}
			$artistID = $artist->id;
		?>
		<div class="col-lg-12 col-md-12 col-12" style="border-top:1px solid #e3e3e3;border-bottom:1px solid #e3e3e3;padding:6px;margin-bottom:12px;background-color:#ebf9ff">
			<div class="col-lg-2 col-md-2 col-2 text-right" style="display:inline-block">
				<a href="profile?id=<?php echo $pins_userIDs[$pin_index]; ?>"><img src="<?php echo $pins_user_images[$pin_index] ?>" style="width:40px;height:40px;border-radius:4px"/></a>
			</div>
			<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
				<a href="profile?id=<?php echo $pins_userIDs[$pin_index]; ?>" style="color:black">
					@<?php echo $pins_usernames[$pin_index]; ?>
				</a>
				<?php if ($pins_levels[$pin_index] == 1) {
					echo '<span style="color:#3075db">&#9679;</span>';
				}
				if ($pins_levels[$pin_index] == 3) {
					echo '<span style="color:#fa4895">&#9679;</span>';
				}
				if ($pins_levels[$pin_index] >= 2) {
					echo '<span style="color:#29cc70">&#9679;</span>';
				} ?>
				<span>
				</span>
			</div>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12 feed-box" style="display:inline-block;margin-top:-12px">
			<a href="artist?id=<?php echo $spotifyID ?>"><img class="width100" src="<?php echo $cover ?>" /></a>
		</div>	
		<div class="col-lg-9 col-md-9 col-9 pd-0-15" style="display:inline-block;">
		<a href="artist?id=<?php echo $spotifyID ?>"><h2><?php echo $artistName ?></h2></a>
		<h4 style="margin-top:-24px"><i><?php echo $artistGenre ?></i></h4>
		</div>
		<h5 class="mgb-0 h6 type--uppercase type--fade  pd-0-15"><?php echo humanTiming(strtotime($dates[$i])); ?></h5>
		<div style="margin-bottom:40px"></div>
	<?php } else if($pins_types[$pin_index] == 'album'){ 
			$album = $api->getAlbum($pins_spotifyIDs[$pin_index]);
	
			$spotifyID = $pins_spotifyIDs[$pin_index];
			$albumName = $album->name;
			$albumArtist = $album->artists[0]->name;
			$cover = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
			if(isset($album->images[0]->url)){
				$cover = $album->images[0]->url;
			}
			$artistID = $album->artists[0]->id;
			$albumID = $album->id;
		?>
		<div class="col-lg-12 col-md-12 col-12" style="border-top:1px solid #e3e3e3;border-bottom:1px solid #e3e3e3;padding:6px;margin-bottom:12px;background-color:#ebf9ff">
			<div class="col-lg-2 col-md-2 col-2 text-right" style="display:inline-block">
				<a href="profile?id=<?php echo $pins_userIDs[$pin_index]; ?>"><img src="<?php echo $pins_user_images[$pin_index] ?>" style="width:40px;height:40px;border-radius:4px"/></a>
			</div>
			<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
				<a href="profile?id=<?php echo $pins_userIDs[$pin_index]; ?>" style="color:black">
					@<?php echo $pins_usernames[$pin_index]; ?>
				</a>
				<?php if ($pins_levels[$pin_index] == 1) {
					echo '<span style="color:#3075db">&#9679;</span>';
				}
				if ($pins_levels[$pin_index] == 3) {
					echo '<span style="color:#fa4895">&#9679;</span>';
				}
				if ($pins_levels[$pin_index] >= 2) {
					echo '<span style="color:#29cc70">&#9679;</span>';
				} ?>
				<span>
				</span>
			</div>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12 feed-box" style="display:inline-block;margin-top:-12px">
			<a href="album?id=<?php echo $albumID ?>"><img class="width100" src="<?php echo $cover ?>" /></a>
		</div>	
		<div class="col-lg-9 col-md-9 col-9 pd-0-15" style="display:inline-block;">
		<a href="album?id=<?php echo $spotifyID ?>"><h2><?php echo $albumName ?></h2></a>
		<a href="artist?id=<?php echo $artistID ?>"><h4 style="margin-top:-24px"><i><?php echo $albumArtist ?></i></h4></a>
		</div>
		<h5 class="mgb-0 h6 type--uppercase type--fade  pd-0-15"><?php echo humanTiming(strtotime($dates[$i])); ?></h5>
		<div style="margin-bottom:40px"></div>
	<?php } ?>
<?php } ?>
</div>

 <?php 
if($item_types[$i] == 'vote' || $item_types[$i] == 'review'){
 $index++;
 }else{
 $pin_index++;
  }
 }
?>
	<div class="more-follow-content">
	</div>
	 <div class="text-center">
		<h3 style="font-weight:bold">- Look likes you've reached the end - </h3>
		<h3>
		Check out <a href="#start" data-toggle-class=".feed-tab:not(.hidden);hidden|#recommended;hidden">your Recommended</a> or the <a href="explore">Explore Page</a> for more content
		</h3>
	 </div>
 <?php } else { ?>
	<div class="boxed boxed--border">
		<span>There's no content to display! Follow some more people or scroll through the Recommended tab!</span>
	</div>
<?php }?>