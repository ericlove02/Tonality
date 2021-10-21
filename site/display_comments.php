<?php
include "db_userdata_connect.php";
include "api_connect.php";
include "db_songs_connect.php";
if (!isset($_SESSION)) {
    session_start();
}

$sql = "SELECT reviews.review_id,reviews.review_content,reviews.review_date,reviews.review_by,data.userID,data.user_name,data.first_name,data.last_name,data.user_image,reviews.review_likes,data.user_level FROM reviews LEFT JOIN data ON reviews.review_by = data.userID WHERE reviews.review_on = " . $_SESSION["current_song"] . " AND NOT is_flagged ORDER BY reviews.review_likes DESC, reviews.review_date DESC LIMIT 30";

$reviewresults = $usersqli->query($sql);

?>

<div class="row">
    <div class="col-lg-12">
        <div class="boxed boxed--lg boxed--border">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="comments">
                        <h4 class="mgb-5">User reviews:</h4>
                        <?php if (($numReviews = $reviewresults->num_rows) > 0) { ?>
                        	<?php if($numReviews == 1){
								echo '<span class="mgb-5">'.$numReviews.' Review</span>';
							}
							else{
								echo '<span class="mgb-5">'.$numReviews.' Reviews</span>';
							}
							?>
	                        <ul class="comments__list">
	                        	<?php while ($row = $reviewresults->fetch_assoc()) { 
									$commenterID = $row['userID'];
							        $user_image = $row['user_image'];
							        $datetime = $row['review_date'];
							        date_default_timezone_set("America/Chicago");
							        $date_string = date("n/j/y g:ia", strtotime($datetime));
							        $commenter = $row['first_name'] . " " . $row['last_name'];
							        $user_name = $row['user_name'];
							        $review = $row['review_content'];
							        $likes = $row['review_likes'];
							        $reviewID = $row['review_id'];
							        $spotifyID = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID=" . $_SESSION["current_song"])->fetch_assoc() ['spotify_uri'];
								?>
	                            <li>
	                                <div class="comment">
	                                    <div class="comment__avatar">
	                                        <a href="profile?id=<?php echo $commenterID ?>"><img alt="Image" src="<?php echo $user_image; ?>">
	                                        </a>
	                                    </div>
	                                    <div class="comment__body">
	                                        <h5 class="type--fine-print">
	                                        	<?php 
	                                        		echo $commenter.'<br>@'.$user_name;
	                                        		if ($row['user_level'] == 1) {
											            echo '<span style="color:#3075db">&#9679;</span>';
											        }
											        if ($row['user_level'] == 3) {
											            echo '<span style="color:#fa4895">&#9679;</span>';
											        }
											        if ($row['user_level'] >= 2) {
											            echo '<span style="color:#29cc70">&#9679;</span>';
											        } 
	                                        	?>
	                                        	
	                                        </h5>
	                                        <div class="comment__meta">
	                                            <span><?php echo $date_string; ?></span>
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
												<svg class="flag-button" id="flag-<?php echo $reviewID ?>" data-id="<?php echo $reviewID ?>" xmlns="http://www.w3.org/2000/svg" style="color:#9c9c9c;float:right;margin-right:-16px" width="20" height="20" fill="currentColor" class="bi bi-flag" viewBox="0 0 16 16">
													<path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z"/>
												</svg>
												<?php if (isset($_SESSION['userID'])) {
													$results = $usersqli->query("SELECT liked_by FROM likes WHERE liked_by=" . $_SESSION['userID'] . " AND liked_review=" . $reviewID);
													if ($results->num_rows > 0) { ?>
														<div style="float:right">
														<svg class="like-button" id="like-<?php echo $reviewID ?>" data-type="liked" data-revId="<?php echo $reviewID ?>" data-spotId="<?php echo $spotifyID ?>" xmlns="http://www.w3.org/2000/svg" width="16" style="color:red;margin: -8px 6px 0;" height="16" fill="currentColor" class="bi bi-heart-fill bi-heart" viewBox="0 0 16 16">
														  <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
														</svg><p id="num-likes-<?php echo $reviewID ?>" style="margin: -8px 10px 0;"><?php echo strval($likes) ?></p></div>
														<!-- <div class='heart-area' style='float:right;margin-top:-14px'><span class='like'><a href='delete_liked_review.php?review=" . $reviewID . "&song=" . $spotifyID . "' style='color:red' onclick='toggleLike(this)' name='red' target=''>&#x2764</a><span style='font-size:12pxmargin-right:10px'>" . strval($likes) . "</span></span></div> -->
													<?php } else { ?>
														<div style="float:right">
														<svg class="like-button" id="like-<?php echo $reviewID ?>" data-type="unliked" data-revId="<?php echo $reviewID ?>" data-spotId="<?php echo $spotifyID ?>" style="margin: -8px 6px 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill bi-heart" viewBox="0 0 16 16">
														  <path d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
														</svg><p id="num-likes-<?php echo $reviewID ?>" style="margin: -8px 10px 0;"><?php echo strval($likes) ?></p></div>
														<!-- <div class='heart-area' style='float:right;margin-top:-14px'><span class='like'><a href='add_liked_review.php?review=" . $reviewID . "&song=" . $spotifyID . "' style='color:black' onclick='toggleLike(this)' name='black' target=''>&#x2764</a><span style='font-size:12px;margin-right:10px'>" . strval($likes) . "</span></span></div> -->
													<?php } 
												} ?>
	                                        </div>
	                                        <p>
	                                            <?php echo $review; ?>
	                                        </p>
	                                    </div>
	                                </div>
	                                <!--end comment-->
	                            </li>
	                            <?php } ?>
	                        </ul>
                    	<?php } else { ?>
                    		<p>No Reviews to display</p>
                    	<?php } 
                    		$mysqli->close();
                    	?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('.like-button').on('click', function(){
	var buttonId = $(this).attr('id');
	var button = document.getElementById(buttonId);
	var likeCount = document.getElementById('num-likes-' + button.getAttribute('data-revid'));
	if(button.getAttribute('data-type') == 'unliked'){
		$.ajax({
			type: "POST",
			url: "add_liked_review.php",
			data: { 'review':button.getAttribute('data-revid'), 'song':button.getAttribute('data-spotid') }
		}).done(function(){
			console.log('review liked');
			button.innerHTML = '<path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>';
			button.style = "color:red;margin: -8px 6px 0;";
			likeCount.innerHTML = parseInt(likeCount.innerHTML) + 1;
			button.setAttribute('data-type', 'liked');
		});
	}
	if(button.getAttribute('data-type') == 'liked'){
		$.ajax({
			type: "POST",
			url: "delete_liked_review.php",
			data: { 'review':button.getAttribute('data-revid'), 'song':button.getAttribute('data-spotid') }
		}).done(function(){
			console.log('review unliked');
			button.innerHTML = '<path d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>';
			button.style = "margin: -8px 6px 0;";
			likeCount.innerHTML = parseInt(likeCount.innerHTML) - 1;
			button.setAttribute('data-type', 'unliked');
		});
	}
});
$('.flag-button').on('click', function(){
	var buttonId = $(this).attr('id');
	var button = document.getElementById(buttonId);
	$.ajax({
		type: "POST",
		url: "flag_review.php",
		data: { 'id':button.getAttribute('data-id') }
	}).done(function(){
		console.log('review flagged');
		button.style = "color:orange;float:right;margin-right:-16px";
	});
});
</script>