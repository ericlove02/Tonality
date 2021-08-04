<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>

<body class="">
<?php include "navbar.php"; ?>

<?php if($_SESSION['user_level'] >= 2){ ?>

<h1 style="width:550px;margin: 20px auto">MODERATOR DASHBOARD</h1>
<div style="width:50%;margin: 0 auto;">
<a href="admin_dashboard.php">Switch to Admin Dashboard</a><br>
<a href="add_article.php">Switch to News Dashboard</a>
</div>
<?php
include "db_userdata_connect.php";
$dayUsers = $usersqli->query("SELECT count(userID) as num FROM data WHERE DATE(user_date) > (NOW() - INTERVAL 1 DAY)")->fetch_assoc()['num'];
$wkUsers = $usersqli->query("SELECT count(userID) as num FROM data WHERE DATE(user_date) > (NOW() - INTERVAL 7 DAY)")->fetch_assoc()['num'];
$moUsers = $usersqli->query("SELECT count(userID) as num FROM data WHERE DATE(user_date) > (NOW() - INTERVAL 30 DAY)")->fetch_assoc()['num'];
$yrUsers = $usersqli->query("SELECT count(userID) as num FROM data WHERE DATE(user_date) > (NOW() - INTERVAL 365 DAY)")->fetch_assoc()['num'];
$totUsers = $usersqli->query("SELECT count(userID) as num FROM data")->fetch_assoc()['num'];

$dayVotes = $usersqli->query("SELECT count(song_id) as num FROM user_votes WHERE DATE(voted_at) > (NOW() - INTERVAL 1 DAY)")->fetch_assoc()['num'];
$wkVotes = $usersqli->query("SELECT count(song_id) as num FROM user_votes WHERE DATE(voted_at) > (NOW() - INTERVAL 7 DAY)")->fetch_assoc()['num'];
$moVotes = $usersqli->query("SELECT count(song_id) as num FROM user_votes WHERE DATE(voted_at) > (NOW() - INTERVAL 30 DAY)")->fetch_assoc()['num'];
$yrVotes = $usersqli->query("SELECT count(song_id) as num FROM user_votes WHERE DATE(voted_at) > (NOW() - INTERVAL 365 DAY)")->fetch_assoc()['num'];
$totVotes = $usersqli->query("SELECT count(song_id) as num FROM user_votes")->fetch_assoc()['num'];

$dayReviews = $usersqli->query("SELECT count(review_id) as num FROM reviews WHERE DATE(review_date) > (NOW() - INTERVAL 1 DAY)")->fetch_assoc()['num'];
$wkReviews = $usersqli->query("SELECT count(review_id) as num FROM reviews WHERE DATE(review_date) > (NOW() - INTERVAL 7 DAY)")->fetch_assoc()['num'];
$moReviews = $usersqli->query("SELECT count(review_id) as num FROM reviews WHERE DATE(review_date) > (NOW() - INTERVAL 30 DAY)")->fetch_assoc()['num'];
$yrReviews = $usersqli->query("SELECT count(review_id) as num FROM reviews WHERE DATE(review_date) > (NOW() - INTERVAL 365 DAY)")->fetch_assoc()['num'];
$totReviews = $usersqli->query("SELECT count(review_id) as num FROM reviews")->fetch_assoc()['num'];

$dayFollows = $usersqli->query("SELECT count(follower_id) as num FROM follows WHERE DATE(followed_at) > (NOW() - INTERVAL 1 DAY)")->fetch_assoc()['num'];
$wkFollows = $usersqli->query("SELECT count(follower_id) as num FROM follows WHERE DATE(followed_at) > (NOW() - INTERVAL 7 DAY)")->fetch_assoc()['num'];
$moFollows = $usersqli->query("SELECT count(follower_id) as num FROM follows WHERE DATE(followed_at) > (NOW() - INTERVAL 30 DAY)")->fetch_assoc()['num'];
$yrFollows = $usersqli->query("SELECT count(follower_id) as num FROM follows WHERE DATE(followed_at) > (NOW() - INTERVAL 365 DAY)")->fetch_assoc()['num'];
$totFollows = $usersqli->query("SELECT count(follower_id) as num FROM follows")->fetch_assoc()['num'];

$dayPosts = $usersqli->query("SELECT count(post_id) as num FROM posts WHERE DATE(post_date) > (NOW() - INTERVAL 1 DAY)")->fetch_assoc()['num'];
$wkPosts = $usersqli->query("SELECT count(post_id) as num FROM posts WHERE DATE(post_date) > (NOW() - INTERVAL 7 DAY)")->fetch_assoc()['num'];
$moPosts = $usersqli->query("SELECT count(post_id) as num FROM posts WHERE DATE(post_date) > (NOW() - INTERVAL 30 DAY)")->fetch_assoc()['num'];
$yrPosts = $usersqli->query("SELECT count(post_id) as num FROM posts WHERE DATE(post_date) > (NOW() - INTERVAL 365 DAY)")->fetch_assoc()['num'];
$totPosts = $usersqli->query("SELECT count(post_id) as num FROM posts")->fetch_assoc()['num'];

$mostVotes = $usersqli->query("SELECT `vote_by`, COUNT(`vote_by`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 7 DAY) GROUP BY `vote_by` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$mostVotesUser = $usersqli->query("SELECT user_name, first_name, last_name, userID, count(user_votes.song_id) AS total FROM data LEFT JOIN user_votes ON vote_by=userID WHERE userID=".$mostVotes['vote_by'])->fetch_assoc();
$mostReviews = $usersqli->query("SELECT `review_by`, COUNT(`review_by`) AS `value_occurrence` FROM `reviews` WHERE DATE(review_date) > (NOW() - INTERVAL 7 DAY) GROUP BY `review_by` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$mostReviewsUser = $usersqli->query("SELECT user_name, first_name, last_name, userID, count(reviews.review_id) AS total FROM data LEFT JOIN reviews ON review_by=userID WHERE userID=".$mostReviews['review_by'])->fetch_assoc();
$mostFollows = $usersqli->query("SELECT `following_id`, COUNT(`following_id`) AS `value_occurrence` FROM `follows` WHERE DATE(followed_at) > (NOW() - INTERVAL 7 DAY) GROUP BY `following_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$mostFollowsUser = $usersqli->query("SELECT user_name, first_name, last_name, userID, count(follows.following_id) AS total FROM data LEFT JOIN follows ON following_id=userID WHERE userID=".$mostFollows['following_id'])->fetch_assoc();

include "db_songs_connect.php";
$wkmostGassed = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 7 DAY) AND vote_type=1 GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$wkmostGassedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$wkmostGassed['song_id'])->fetch_assoc();
$wkmostTrashed = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 7 DAY) AND vote_type=0 GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$wkmostTrashedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$wkmostTrashed['song_id'])->fetch_assoc();
$wkmostVoted = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 7 DAY) GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$wkmostVotedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$wkmostVoted['song_id'])->fetch_assoc();

$momostGassed = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 30 DAY) AND vote_type=1 GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$momostGassedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$momostGassed['song_id'])->fetch_assoc();
$momostTrashed = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 30 DAY) AND vote_type=0 GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$momostTrashedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$momostTrashed['song_id'])->fetch_assoc();
$momostVoted = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 30 DAY) GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$momostVotedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$momostVoted['song_id'])->fetch_assoc();

$yrmostGassed = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 365 DAY) AND vote_type=1 GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$yrmostGassedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$yrmostGassed['song_id'])->fetch_assoc();
$yrmostTrashed = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 365 DAY) AND vote_type=0 GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$yrmostTrashedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$yrmostTrashed['song_id'])->fetch_assoc();
$yrmostVoted = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 365 DAY) GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 1")->fetch_assoc();
$yrmostVotedSong = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$yrmostVoted['song_id'])->fetch_assoc();

$songIds = [$wkmostGassedSong['spotify_uri'], $wkmostTrashedSong['spotify_uri'], $wkmostVotedSong['spotify_uri'], $momostGassedSong['spotify_uri'], $momostTrashedSong['spotify_uri'], $momostVotedSong['spotify_uri'], $yrmostGassedSong['spotify_uri'], $yrmostTrashedSong['spotify_uri'], $yrmostVotedSong['spotify_uri']];

$newUsers = $usersqli->query("SELECT first_name, last_name, user_image, user_name, userID FROM data ORDER BY user_date DESC LIMIT 30");

$flagged = $usersqli->query("SELECT post_by AS user_id, post_content AS content, post_id AS content_id, 'post' AS content_type FROM posts WHERE is_flagged UNION SELECT review_by AS user_id, review_content AS content, review_id AS content_id, 'review' AS content_type FROM reviews WHERE is_flagged");

$mostGassedThisWeek = $usersqli->query("SELECT `song_id`, COUNT(`song_id`) AS `value_occurrence` FROM `user_votes` WHERE DATE(voted_at) > (NOW() - INTERVAL 7 DAY) AND vote_type=1 GROUP BY `song_id` ORDER BY `value_occurrence` DESC LIMIT 10");
while($row = $mostGassedThisWeek->fetch_assoc()){
	$results = $mysqli->query("SELECT spotify_uri, song_pos_ratings AS pos, song_total_ratings AS tot FROM ratings WHERE songID = ".$row['song_id'])->fetch_assoc();
	$gassedThisWeekSpotIds[] = $results['spotify_uri'];
	$gassedThisWeekTotRatings[] = $results['tot'];
	$gassedThisWeekPosRatings[] = $results['pos'];
	$gassedThisWeekValueOccurences[] = $row['value_occurrence'];
}


$mysqli->close();


include "api_connect.php";
$songData = $api->getTracks($songIds);

$mostGassedThisWeekTracks = $api->getTracks($gassedThisWeekSpotIds);

require "rating_system.php";

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
			<h2>TONALITY DATA</h2>
			<p>This is private data. Do not share this data in any form without permission</p>
			<table>
				<tr>
					<th></th>
					<th>Today</th>
					<th>This Week</th>
					<th>This Month</th>
					<th>This Year</th>
					<th>Total</th>
				</tr>
				<tr>
					<td>New Users</td>
					<td><?php echo $dayUsers ?></td>
					<td><?php echo $wkUsers ?></td>
					<td><?php echo $moUsers ?></td>
					<td><?php echo $yrUsers ?></td>
					<td><?php echo $totUsers ?></td>
				</tr>
				<tr>
					<td>Song Votes</td>
					<td><?php echo $dayVotes ?></td>
					<td><?php echo $wkVotes ?></td>
					<td><?php echo $moVotes ?></td>
					<td><?php echo $yrVotes ?></td>
					<td><?php echo $totVotes ?></td>
				</tr>
				<tr>
					<td>Song Reviews</td>
					<td><?php echo $dayReviews ?></td>
					<td><?php echo $wkReviews ?></td>
					<td><?php echo $moReviews ?></td>
					<td><?php echo $yrReviews ?></td>
					<td><?php echo $totReviews ?></td>
				</tr>
				<tr>
					<td>Follows</td>
					<td><?php echo $dayFollows ?></td>
					<td><?php echo $wkFollows ?></td>
					<td><?php echo $moFollows ?></td>
					<td><?php echo $yrFollows ?></td>
					<td><?php echo $totFollows ?></td>
				</tr>
				<tr>
					<td>Forum Posts</td>
					<td><?php echo $dayPosts ?></td>
					<td><?php echo $wkPosts ?></td>
					<td><?php echo $moPosts ?></td>
					<td><?php echo $yrPosts ?></td>
					<td><?php echo $totPosts ?></td>
				</tr>
			</table>
			<table>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Username</th>
					<th>User Id</th>
					<th>Related Stat (Week)</th>
					<th>Related Stat (Total)</th>
				</tr>
				<tr>
					<td>Most Votes User (Week)</td>
					<td><?php echo $mostVotesUser['first_name'] . " " . $mostVotesUser['last_name'] ?></td>
					<td><?php echo "<a href='profile?id=".$mostVotesUser['userID'] ."' target='_blank'>" .$mostVotesUser['user_name'] . "</a>" ?></td>
					<td><?php echo $mostVotesUser['userID'] ?></td>
					<td><?php echo $mostVotes['value_occurrence'] ?></td>
					<td><?php echo $mostVotesUser['total'] ?></td>
				</tr>
				<tr>
					<td>Most Reviews User (Week)</td>
					<td><?php echo $mostReviewsUser['first_name'] . " " . $mostReviewsUser['last_name'] ?></td>
					<td><?php echo "<a href='profile?id=".$mostReviewsUser['userID'] ."' target='_blank'>" .$mostReviewsUser['user_name'] . "</a>" ?></td>
					<td><?php echo $mostReviewsUser['userID'] ?></td>
					<td><?php echo $mostReviews['value_occurrence'] ?></td>
					<td><?php echo $mostReviewsUser['total'] ?></td>
				</tr>
				<tr>
					<td>Most Followed User (Week)</td>
					<td><?php echo $mostFollowsUser['first_name'] . " " . $mostFollowsUser['last_name'] ?></td>
					<td><?php echo "<a href='profile?id=".$mostFollowsUser['userID'] ."' target='_blank'>" .$mostFollowsUser['user_name'] . "</a>" ?></td>
					<td><?php echo $mostFollowsUser['userID'] ?></td>
					<td><?php echo $mostFollows['value_occurrence'] ?></td>
					<td><?php echo $mostFollowsUser['total'] ?></td>
				</tr>
			</table>
			<table>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Artist</th>
					<th>Rating</th>
					<th>Related Stat</th>
				</tr>
				<tr>
					<td>Most Gassed Song (Week)</td>
					<td><?php echo $songData->tracks[0]->name; ?></td>
					<td><?php echo $songData->tracks[0]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($wkmostGassedSong['pos'], $wkmostGassedSong['tot']) ?></td>
					<td><?php echo $wkmostGassed['value_occurrence'] ?></td>
				</tr>
				<tr>
					<td>Most Trashed Song (Week)</td>
					<td><?php echo $songData->tracks[1]->name; ?></td>
					<td><?php echo $songData->tracks[1]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($wkmostTrashedSong['pos'], $wkmostTrashedSong['tot']) ?></td>
					<td><?php echo $wkmostTrashed['value_occurrence'] ?></td>
				</tr>
				<tr>
					<td>Most Voted On Song (Week)</td>
					<td><?php echo $songData->tracks[2]->name; ?></td>
					<td><?php echo $songData->tracks[2]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($wkmostVotedSong['pos'], $wkmostVotedSong['tot']) ?></td>
					<td><?php echo $wkmostVoted['value_occurrence'] ?></td>
				</tr>
				<tr>
					<td>Most Gassed Song (Month)</td>
					<td><?php echo $songData->tracks[3]->name; ?></td>
					<td><?php echo $songData->tracks[3]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($momostGassedSong['pos'], $momostGassedSong['tot']) ?></td>
					<td><?php echo $momostGassed['value_occurrence'] ?></td>
				</tr>
				<tr>
					<td>Most Trashed Song (Month)</td>
					<td><?php echo $songData->tracks[4]->name; ?></td>
					<td><?php echo $songData->tracks[4]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($momostTrashedSong['pos'], $momostTrashedSong['tot']) ?></td>
					<td><?php echo $momostTrashed['value_occurrence'] ?></td>
				</tr>
				<tr>
					<td>Most Voted On Song (Month)</td>
					<td><?php echo $songData->tracks[5]->name; ?></td>
					<td><?php echo $songData->tracks[5]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($momostVotedSong['pos'], $momostVotedSong['tot']) ?></td>
					<td><?php echo $momostVoted['value_occurrence'] ?></td>
				</tr>
				<tr>
					<td>Most Gassed Song (Year)</td>
					<td><?php echo $songData->tracks[6]->name; ?></td>
					<td><?php echo $songData->tracks[6]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($yrmostGassedSong['pos'], $yrmostGassedSong['tot']) ?></td>
					<td><?php echo $yrmostGassed['value_occurrence'] ?></td>
				</tr>
				<tr>
					<td>Most Trashed Song (Year)</td>
					<td><?php echo $songData->tracks[7]->name; ?></td>
					<td><?php echo $songData->tracks[7]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($wkmostTrashedSong['pos'], $yrmostTrashedSong['tot']) ?></td>
					<td><?php echo $yrmostTrashed['value_occurrence'] ?></td>
				</tr>
				<tr>
					<td>Most Voted On Song (Year)</td>
					<td><?php echo $songData->tracks[8]->name; ?></td>
					<td><?php echo $songData->tracks[8]->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($yrmostVotedSong['pos'], $yrmostVotedSong['tot']) ?></td>
					<td><?php echo $yrmostVoted['value_occurrence'] ?></td>
				</tr>
			</table>
			
			<h2>Reported Content</h2>
			<table>
				<tr>
					<th>User ID</th>
					<th>Content</th>
					<th>Link</th>
					<th>Delete</th>
					<th>Approve</th>
				</tr>
				<?php if($flagged->num_rows > 0){
				while($post = $flagged->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $post['user_id'] ?></td>
					<td><?php echo $post['content'] ?></td>
					<td><a href="profile?id=<?php echo $post['user_id'] ?>">Link</a></td>
					<td>
					<?php if($post['content_type'] == 'review'){ ?>
						<a href="delete_review.php?review=<?php echo $post['content_id'] ?>" target="target" onclick="reload()">
					<?php }else if($post['content_type'] == 'post'){ ?>
						<a href="delete_post.php?post=<?php echo $post['content_id'] ?>" target="target" onclick="reload()">
					<?php } ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" style="color:red" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
					  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
					  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
					</svg></td>
					<td>
					<?php if($post['content_type'] == 'review'){ ?>
						<a href="unflag_review.php?id=<?php echo $post['content_id'] ?>" target="target" onclick="reload()">
					<?php }else if($post['content_type'] == 'post'){ ?>
						<a href="unflag_post.php?id=<?php echo $post['content_id'] ?>" target="target" onclick="reload()">
					<?php } ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" style="color:green" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
					  <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
					  <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
					</svg></a></td>
				</tr>
				<?php }
				}else{ ?>
				<p>All content has been reviewed</p>
				<?php } ?>
			</table>
			
			<h2>Recently Created Profiles</h2>
			<table>
				<tr>
					<th>Account Name</th>
					<th>Image</th>
					<th>Username</th>
					<th>User Id</th>
					<th>Votes</th>
				</tr>
				<?php while($acct = $newUsers->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $acct['first_name'] . " " . $acct['last_name'] ?></td>
					<td><img style="height:30px;width:30px" src="<?php echo $acct['user_image'] ?>" /></td>
					<td><?php echo "<a href='profile?id=".$acct['userID'] ."' target='_blank'>" .$acct['user_name'] . "</a>" ?></td>
					<td><?php echo $acct['userID'] ?></td>
					<td><?php echo $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $acct['userID'])->num_rows; ?></td>
				</tr>
				<?php } ?>
			</table>
			
			<h2>Top 10 Most Gassed This Week</h2>
			<table>
				<tr>
					<th>Name</th>
					<th>Artist</th>
					<th>Unwt Rating</th>
					<th>Wt Rating</th>
					<th># Gas this Week</th>
				</tr>
				<?php 
				$index = 0;
				foreach($mostGassedThisWeekTracks->tracks as $track){ ?>
				<tr>
					<td><?php echo $track->name; ?></td>
					<td><?php echo $track->artists[0]->name; ?></td>
					<td><?php echo get_rating_no_pop($gassedThisWeekPosRatings[$index], $gassedThisWeekTotRatings[$index]) ?></td>
					<td><?php echo get_rating($gassedThisWeekPosRatings[$index], $gassedThisWeekTotRatings[$index], $track->popularity) ?></td>
					<td><?php echo $gassedThisWeekValueOccurences[$index] ?></td>
				</tr>
				<?php 
				$index++;
				} ?>
			</table>
			
		</div>
	</div>
</div>

<?php }else{ ?>
<br>
<br>
<p style="padding: 20px;margin-left:25%">This page is restricted to Tonality Moderators only</p>
<?php 
}

include "footer.php";
include "script.php";
?>

</body>
</html>