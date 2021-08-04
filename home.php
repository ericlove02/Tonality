<?php
if(!isset($_SESSION)){
	session_start();
}
/*if(!isset($_SESSION['signed_in']) || !$_SESSION['signed_in']){
	header('Location: index');
}*/

include 'Mobile_Detect.php';
$detect = new Mobile_Detect();

$isMobile = $detect->isMobile();

?>
<!DOCTYPE html>
<html lang="en" style="">
    <head>
        <?php include "head.php"; ?>
		<style>
		body, html { overflow: hidden; <?php if($isMobile){ echo 'overflow-x:hidden !important;'; } ?> }
		.click-btn:hover{
			cursor: pointer;
		}
		.LockOn {
			display: block;
			visibility: visible;
			position: fixed;
			z-index: 999;
			top: 0px;
			left: 0px;
			width: 105%;
			height: 105%;
			background-color:#fcfcfc;
			vertical-align:top;
			padding-top: 20%; 
			filter: alpha(opacity=80); 
			opacity: .8; 
			font-size:large;
			color:blue;
			font-style:italic;
			font-weight:400;
		}
		.LockOn img{
		    width:100px;
		    height:100px;
		    position:fixed;
		    top:calc(50vh - 50px);
		    left:calc(50vw - 50px);
		}
		</style>
    </head>
    <body style="background-color:#fcfcfc;" class="">
	<div id="coverScreen"  class="LockOn">
        <img src="images/loading.gif" />
	</div>

		<div style="position:fixed;width:100vw;height:inherit;z-index:1;background-color:#f2f2f2">
			<?php include "navbar.php";  ?>
		</div>
			<div class="main-container" style="padding-top:80px">
            <section class="space--sm">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <?php if(!$isMobile){ echo '<div class="boxed boxed--border" style="margin-top:-24px;padding:-12px;">'; } ?>
                                <?php include "new_releases.php"; ?>
                            </div>

							<div class="col-lg-6 col-6  text-right" style="display:inline-block;border-right:1px solid gray;">
								<a href="#" data-toggle-class=".feed-tab:not(.hidden);hidden|#recommended;hidden"><h3 style="margin-bottom:-2px;color:#e5771e;text-decoration:underline" id="recommended-tab" onclick="recClicked()">Recommended</h3></a>
							</div>
							<div class="col-lg-5 col-5" style="display:inline-block;margin-bottom:10px;">
								<a href="#" data-toggle-class=".feed-tab:not(.hidden);hidden|#following;hidden"><h3 style="margin-bottom:-2px" id="following-tab" onclick="followClicked()">Following</h3></a>
							</div>

							<?php 
							include "db_userdata_connect.php";
							?>
							
	<!----------- FOLLOWING FEED ------------>
							<div id="following" class="hidden feed-tab" style="margin-top:10px">
							<?php
							require_once "rating_system.php";
							
							function humanTiming ($time){
								    $time = time() - $time - 3600;
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
														LIMIT 0, 12	");
							echo "<span style='display:none' id='num_results'>12</span>";

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
							 
							 for($i = 0; $i < 12; $i++){ 
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
								<?php if(isset($_SESSION['signed_in']) && $_SESSION['signed_in']){ ?>
								<div class="boxed boxed--border">
									<span>There's no content to display! Follow some more people or scroll through the Recommended tab!</span>
								</div>
								<?php }else{ ?>
								<div class="boxed boxed--border">
									<span><a href="login">Sign in</a> and follow some users or scroll through the recommended tab</span>
								</div>
								<?php } ?>
							<?php }?>
                        </div>

	<!---------- END FOLLOWING FEED ---------->
						<div class="feed-tab" id="recommended" style="margin-top:10px">
							<?php
							require_once "rating_system.php";
							include "db_songs_connect.php";
							include "api_connect.php";
							include "db_userdata_connect.php";
							
							if(isset($_SESSION["userID"]) && $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " ORDER BY voted_at DESC")->num_rows >= 1){
							
								$gassedSongs = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=1 ORDER BY voted_at DESC LIMIT 5");
								$songs = array();
								while($row = $gassedSongs->fetch_assoc()){
									$songID = $row['song_id'];
									$spotifyID = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . trim($songID))->fetch_assoc() ['spotify_uri'];
									array_push($songs, $spotifyID);
								}
								$results = $api->getRecommendations(['seed_tracks' => $songs, 'limit' => 12]);
								
							}else{
								$results =$api->getRecommendations(['seed_tracks' => $api->getPlaylistTracks('spotify:playlist:37i9dQZF1DXcBWIGoYBM5M', ['limit' => 1])->items[0]->track->id, 'limit'=>12]);
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
								if(($index + 1) % 12 == 0){ ?>
									<!-- Ad here -->
									
								<?php
								}
							?>
							<div class="<?php if(!$isMobile){ echo "boxed boxed--border"; } ?> feed-box <?php if($index == 3){ echo "recommend-trigger"; } ?>" style="<?php if($isMobile){ echo "width:100vw;margin-left:-15px;"; } ?>">		
									<div class="col-lg-12 col-md-12 col-sm-12 feed-box" style="display:inline-block;margin-top:0">
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
						</div>
						</div>
                        <div class="col-lg-4" id="mobile-none" style="max-width:300px">
						<div style="position:sticky;top:130px;position: -webkit-sticky;">
						
						<?php if(isset($_SESSION['userID'])){ ?>
							<div style="display:inline-block;margin-bottom:20px" class="clickable-row" data-href="profile?id=<?php echo $_SESSION['userID'] ?>">
								<?php 
								include "db_userdata_connect.php";
								$profile = $usersqli->query("SELECT user_image, user_name, first_name, last_name FROM data WHERE userID = " . $_SESSION['userID']);
								$row = $profile->fetch_assoc();
								?>
								<div class="col-lg-4" style="display:inline-block">
									<img style="border-radius:5px;margin-top:-32px" src="<?php echo $row['user_image'] ?>" />
								</div>
								<div class="col-lg-7" style="display:inline-block;">
									<span style="font-weight:bold;font-size:20px;color:black"><?php echo $row['user_name'] ?></span>
									<span class="mgb-0 h6 type--uppercase type--fade"><?php echo $row['first_name'] . " " . $row['last_name'] ?></span>
								</div>
							</div>
							<h4>Suggested Users</h4>
                            <ul style="margin-top:-18px"> 
								<li style="margin-top:2px">
								<div class="col-lg-3" style="display:inline-block">
									<a href="profile?id=4"><img src="images/tonality-icon.png" /></a>
								</div>
								<div class="col-lg-6" style="display:inline-block" data-href="profile?id=4">
									<a href="profile?id=4" style="color:#666666"><span>Tonality</span></a>
								</div>
								<div class="col-lg-2" style="display:inline-block">
									<?php if (isset($_SESSION["userID"]) && !($_SESSION["userID"] == 4)) { 
										if ($usersqli->query("SELECT follower_id FROM follows WHERE follower_id=".$_SESSION['userID']." AND following_id=4")->num_rows > 0) {
										?>
											<span id="follow-button-4" style="color: #66b9ed;" class="click-btn" data-follow="4" data-user="<?php echo $_SESSION["userID"] ?>">Unfollow</span>
										<?php } else { ?>
											<span id="follow-button-4" style="color: #66b9ed;" class="click-btn" data-follow="4" data-user="<?php echo $_SESSION["userID"] ?>">Follow</span>
										<?php } ?>
									<?php } ?>
								</div>
								</li>
								<?php
								$sql = "Select data.userID, data.user_name, data.first_name, data.last_name, data.user_image, data.user_level, count(user_votes.vote_by) as num_votes
										From data 
										left join user_votes 
										on data.userID=user_votes.vote_by
										where data.userID<>".$_SESSION['userID']." and data.userID<>4
										group by data.userID, data.user_name
										order by RAND()
										limit 4
									";
								$profiles = $usersqli->query($sql);
								while($row = $profiles->fetch_assoc()){
								?>
									<li style="margin-top:2px">

									<div class="col-lg-3" style="display:inline-block">
										<a href="profile?id=<?php echo $row['userID'] ?>"><img src="<?php echo $row['user_image'] ?>" style="border-radius:2px" /></a>
									</div>
									<div class="col-lg-6" style="display:inline-block">
										<a href="profile?id=<?php echo $row['userID'] ?>" style="color:#666666;"><span><?php if(strlen($row['user_name'])<18){echo mb_substr($row['user_name'],0,18,'utf-8');}else{ echo mb_substr($row['user_name'],0,15,'utf-8') . "..."; } ?></span></a>
									</div>

									<div class="col-lg-2" style="display:inline-block">
										<?php if (isset($_SESSION["userID"]) && !($_SESSION["userID"] == $row["userID"])) { 
											if ($usersqli->query("SELECT follower_id FROM follows WHERE follower_id=".$_SESSION['userID']." AND following_id=".$row["userID"])->num_rows > 0) {
											?> 
												<span id="follow-button-<?php echo $row["userID"]; ?>" style="color: #66b9ed;" class="click-btn" data-follow="<?php echo $row["userID"]; ?>" data-user="<?php echo $_SESSION["userID"] ?>">Unfollow</span>
											<?php } else { ?>
												<span id="follow-button-<?php echo $row["userID"]; ?>" style="color: #66b9ed;" class="click-btn" data-follow="<?php echo $row["userID"]; ?>" data-user="<?php echo $_SESSION["userID"] ?>">Follow</span>
											<?php } ?>
										<?php } ?>
									</div>
									</li>
								<?php } ?>
							</ul>
						<?php }else{ ?>
						
						<?php } ?>
						
						<div style="margin-bottom:12px;max-width:100%;width:fit-content;justify-content:flex-start;margin:0 auto">
							<div style="margin-right:4px;margin-left:4px;margin-bottom:2px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
								<a href="help" class="mgb-0 h6 type--fade">Help</a>
							</div>
							<div style="margin-right:4px;margin-left:4px;margin-bottom:2px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
								<a href="contact" class="mgb-0 h6 type--fade">Contact</a>
							</div>
							<div style="margin-right:4px;margin-left:4px;margin-bottom:2px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
								<a href="about-us" class="mgb-0 h6 type--fade">About</a>
							</div>
							<div style="margin-right:4px;margin-left:4px;margin-bottom:2px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
								<a href="terms" class="mgb-0 h6 type--fade">Agreements</a>
							</div>
							<div class="col-md-7">
								<span class="type--fine-print" style="white-space:nowrap">&copy;
									<span class="update-year"></span> Tonality</span>
								</div>
							</div>
						</div>
						
						</div>
                        </div>
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
        </div>

			
			<?php
            include "footer.php";
            include "script.php";
        ?>

		<script async>
		$(window).on('beforeunload', function(){
		  window.scrollTo({top: 0});
		  $("#coverScreen").show();
		});
		$(window).on('load', function () {
		$("#coverScreen").hide();
		document.getElementsByTagName('body')[0].style.overflow = 'visible';
		document.getElementsByTagName('html')[0].style.overflow = 'visible';
		});

		
		function followClicked(){
			document.getElementById('following-tab').style.color = '#e5771e';
			document.getElementById('recommended-tab').style.color = 'black';
			document.getElementById('following-tab').style.textDecoration = 'underline';
			document.getElementById('recommended-tab').style.textDecoration = '';
			window.scrollTo({top: 0, behavior: 'smooth'});
		}
		function recClicked(){
			document.getElementById('following-tab').style.color = 'black';
			document.getElementById('recommended-tab').style.color = '#e5771e';
			document.getElementById('following-tab').style.textDecoration = '';
			document.getElementById('recommended-tab').style.textDecoration = 'underline';
			window.scrollTo({top: 0, behavior: 'smooth'});
		}
		
		$('.click-btn').on('click', function(){
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
		function elementInView(elem){
		    var top_of_element = $(elem).offset().top;
			var bottom_of_element = $(elem).offset().top + $(elem).outerHeight();
			var bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
			var top_of_screen = $(window).scrollTop();

			return (bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element);
		};
		$(window).scroll(function(){
			if (elementInView($('.recommend-trigger'))){
				$('.recommend-trigger').removeClass("recommend-trigger");
				console.log('load more content');
				$.ajax({
					type: "POST",
					url: "load_more_recommendations.php"
				}).done(function(content){
					console.log(content);
					$('.more-content').replaceWith(content);
					console.log("CONTENT LOADED");
				});
				
			}
			if (elementInView($('.following-trigger'))){
				$('.following-trigger').removeClass("following-trigger");
				console.log('load more following content');
				$.ajax({
					type: "POST",
					url: "load_more_following.php",
					data: { 'start_num':document.getElementById('num_results').innerHTML }
				}).done(function(content){
					console.log(content);
					$('.more-follow-content').replaceWith(content);
					console.log("CONTENT LOADED");
					document.getElementById('num_results').innerHTML = parseInt(document.getElementById('num_results').innerHTML) + 50;
				});
				
			}
		});

		var docWidth = document.documentElement.offsetWidth;

		[].forEach.call(
		  document.querySelectorAll('*'),
		  function(el) {
			if (el.offsetWidth > docWidth) {
			  console.log(el);
			}
		  }
		);
		</script>
    </body>

</html>
