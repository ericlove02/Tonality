<?php  
$sql = "SELECT
			reviews.review_id,
			reviews.review_content,
			reviews.review_date,
			reviews.review_by,
			data.userID,
			data.user_name,
			data.first_name,
			data.last_name,
			data.user_image,
			reviews.review_likes,
			reviews.review_on
		FROM
			reviews
		LEFT JOIN
			data
		ON
			reviews.review_by = data.userID
		WHERE
			data.userID = " . $_POST["profile-id"]] . " ORDER BY
			reviews.review_likes DESC,
			reviews.review_date DESC
		LIMIT ". $_POST['start_num'].", 50";
$result = $usersqli->query($sql);

?>

<?php if ($result->num_rows > 0) { ?>
	<?php 
	unset($spotifyIDs);
	unset($songIDs);
	unset($posRatings);
	unset($totRatings);
	
	$index = 0;
	
		while ($row = $result->fetch_assoc()) { 
		
			$commenterIDs[] = $row['userID'];
			$user_images[] = $row['user_image'];
			$datetime = $row['review_date'];
			date_default_timezone_set("America/Chicago");
			$date_strings[] = date("n/j/y g:ia", strtotime($datetime));
			$commenters[] = $row['first_name'] . " " . $row['last_name'];
			$user_names[] = $row['user_name'];
			$reviews[] = $row['review_content'];
			$songID = $row['review_on'];
			$reviewIDs[] = $row['review_id'];
			$likesList[] = $row['review_likes'];

			$resultsong = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . $songID . " LIMIT 1");
			$spotifyIDs[] = $resultsong->fetch_assoc() ['spotify_uri'];
		
		}
	
	$tracks = $api->getTracks($spotifyIDs);
	
	foreach($tracks->tracks as $track){
		$commenterID = $commenterIDs[$index];
		$user_image = $user_images[$index];
		$date_string = $date_strings[$index];
		$commenter = $commenters[$index];
		$user_name = $user_names[$index];
		$review = $reviews[$index];
		$reviewID = $reviewIDs[$index];
		$likes = $likesList[$index];
		
		$cover = $track->album->images[0]->url;
		$name = $track->name;
		$artist = $track->artists[0]->name;
		$albumID = $track->album->id;
		$artistID = $track->artists[0]->id;
		$name = $track->name;
	?>
	<div class="boxed boxed--border <?php if(($index+1)%40 == 0){ echo " review-trigger"; } ?>">
		<div class="row">
			<div class="col-md-2 clickable-row" data-href="album?id=<?php echo $albumID; ?>">
				<img alt="avatar" src="<?php echo $cover; ?>"/>
			</div>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-6 text-left">
						<h5 class="mgb-0 clickable-row" data-href="song?id=<?php echo $spotifyID; ?>"><?php echo $name; ?> 
						</h5>
					</div>
					<div class="col-md-6 text-right">
						<span class="text-right"><?php echo $date_string; ?></span>
					</div>
				</div>
				<a href="artist?id=<?php echo $artistID; ?>">
					<?php echo $artist; ?>
				</a><br>
				<div class="row">
					<div class="col-md-9 text-left">
						<p class="lead"><?php echo $review; ?></p>
					</div>
					<div class="col-md-3 text-right">
						<?php
							if (isset($_SESSION['userID'])) {
								$user_level = $usersqli->query("SELECT user_level FROM data WHERE userID = " . $_SESSION['userID'])->fetch_assoc() ['user_level'];
								if ($_SESSION['userID'] == $row['review_by'] || $user_level >= 2) {
									echo "<a href='delete_review.php?review=" . $reviewID . "' style='float:right' target='target' onclick='reload()'><svg xmlns=\"http://www.w3.org/2000/svg\" style=\"color:red;width:36px;height:36px;margin-top:-10px;\" fill=\"currentColor\" class=\"bi bi-x\" viewBox=\"0 0 16 16\">
										  <path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"/>
										</svg></a>";
								}
							}
						?>
						<svg class="flag-button" id="flag-<?php echo $reviewID ?>" data-id="<?php echo $reviewID ?>" xmlns="http://www.w3.org/2000/svg" style="color:#9c9c9c;float:right;margin-right:-4px" width="20" height="20" fill="currentColor" class="bi bi-flag" viewBox="0 0 16 16">
							<path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z"/>
						</svg>
						<?php if (isset($_SESSION['userID'])) {
							$results = $usersqli->query("SELECT liked_by FROM likes WHERE liked_by=" . $_SESSION['userID'] . " AND liked_review=" . $reviewID);
							if ($results->num_rows > 0) { ?>
								<div style="float:right">
								<svg class="like-button" id="like-<?php echo $reviewID ?>" data-type="liked" data-revId="<?php echo $reviewID ?>" data-prof="<?php echo $commenterID ?>" xmlns="http://www.w3.org/2000/svg" width="16" style="color:red;margin: -8px 6px 0;" height="16" fill="currentColor" class="bi bi-heart-fill bi-heart" viewBox="0 0 16 16">
								  <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
								</svg><p id="num-likes-<?php echo $reviewID ?>" style="margin: -8px 10px 0;"><?php echo strval($likes) ?></p></div>
								<!-- <div class='heart-area' style='float:right;margin-top:-14px'><span class='like'><a href='delete_liked_review.php?review=" . $reviewID . "&song=" . $spotifyID . "' style='color:red' onclick='toggleLike(this)' name='red' target=''>&#x2764</a><span style='font-size:12pxmargin-right:10px'>" . strval($likes) . "</span></span></div> -->
							<?php } else { ?>
								<div style="float:right">
								<svg class="like-button" id="like-<?php echo $reviewID ?>" data-type="unliked" data-revId="<?php echo $reviewID ?>" data-prof="<?php echo $commenterID ?>" style="margin: -8px 6px 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill bi-heart" viewBox="0 0 16 16">
								  <path d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
								</svg><p id="num-likes-<?php echo $reviewID ?>" style="margin: -8px 10px 0;"><?php echo strval($likes) ?></p></div>
								<!-- <div class='heart-area' style='float:right;margin-top:-14px'><span class='like'><a href='add_liked_review.php?review=" . $reviewID . "&song=" . $spotifyID . "' style='color:black' onclick='toggleLike(this)' name='black' target=''>&#x2764</a><span style='font-size:12px;margin-right:10px'>" . strval($likes) . "</span></span></div> -->
							<?php } 
						} ?>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<?php 
	$index++;
	} ?>
	<div class="more-trashed-content">
	</div>