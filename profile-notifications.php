<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <?php
            include "db_userdata_connect.php";
			include "db_songs_connect.php";
			include "api_connect.php";
            $sql = "SELECT * FROM data WHERE userID=" . $_SESSION["userID"];
			$result = $usersqli->query($sql);
			$row = $result->fetch_assoc();
			$name = $row["first_name"] . " " . $row["last_name"];
            if (!isset($_SESSION)) {
			    session_start();
			}
			$viewed_profile = $_SESSION["userID"];
        ?>
        <div class="main-container">
            <section class="bg--secondary space--sm">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="boxed boxed--lg boxed--border">
                                <div class="text-block text-center">
                                    <img alt="avatar" src="<?php echo $row["user_image"];?>"  />
                                    <span class="h5"><?php echo $name; ?></span>
                                    <h4>@<?php echo $row["user_name"] ?> </h4>
                                    <?php if ($row['user_level'] == 1) { ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" style="color:#3f729b" height="18" width="18" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
										</svg>
                                    <?php } if ($row['user_level'] == 3) {?>
                                        <a data-tooltip="Tonality Developer"><svg xmlns="http://www.w3.org/2000/svg" style="color:#fa4895" height="18" width="18" fill="currentColor" class="bi bi-code-slash" viewBox="0 0 16 16">
										  <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294l4-13zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0zm6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0z"/>
										</svg></a>
                                    <?php } if ($row['user_level'] >= 2) { ?>
                                        <a data-tooltip="Tonality Mod"><svg xmlns="http://www.w3.org/2000/svg" style="color:#1CB96A" height="18" width="18" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
										  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
										  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
										</svg></a>
                                    <?php } 
									$songsRated = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"])->num_rows;
									if (50 <= $songsRated && $songsRated < 300) { ?>
                                        <a data-tooltip="50 Songs Rated"><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:#CD7F32" height="18" width="18" fill="currentColor" class="bi bi-vinyl" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM4 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
										  <path d="M9 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
										</svg></a>
                                    <?php } if (300 <= $songsRated && $songsRated < 1000) {?>
                                        <a data-tooltip="300 Songs Rated"><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" data-tooltip="300+ Songs Rated" style="color:#C0C0C0" height="18" width="18" fill="currentColor" class="bi bi-vinyl" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM4 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
										  <path d="M9 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
										</svg></a>
                                    <?php } if (1000 <= $songsRated && $songsRated < 5000) { ?>
                                        <a data-tooltip="1000 Songs Rated"><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:	#FFD700" height="18" width="18" fill="currentColor" class="bi bi-vinyl" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM4 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
										  <path d="M9 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
										</svg></a>
                                    <?php } if (5000 <= $songsRated) { ?>
                                        <a data-tooltip="5000 Songs Rated"><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:	red" height="18" width="18" fill="currentColor" class="bi bi-vinyl" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM4 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
										  <path d="M9 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
										</svg></a>
                                    <?php } 
									$numComments = $usersqli->query("SELECT review_by, count(review_by) AS num FROM reviews WHERE reviews.review_by =" . $_SESSION['userID'])->fetch_assoc() ['num'];
									if (5 <= $numComments && $numComments < 20) { ?>
                                        <a data-tooltip="5 Songs Reviewed"><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:#CD7F32" height="18" width="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
										  <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
										</svg></a>
                                    <?php } if (20 <= $numComments && $numComments < 50) {?>
                                        <a data-tooltip="20 Songs Reviewed"><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:#C0C0C0" height="18" width="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
										  <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
										</svg></a>
                                    <?php } if (50 <= $numComments && $numComments < 200) { ?>
                                        <a data-tooltip="50 Songs Reviewed"><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:	#FFD700" height="18" width="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
										  <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
										</svg></a>
                                    <?php } if (200 <= $numComments) { ?>
                                        <a data-tooltip="200 Songs Reviewed"><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:	red" height="18" width="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
										  <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
										</svg></a>
                                    <?php } ?>
                                    
                                   
                                    
                                </div>
                                <hr>
                                <?php
                                    $name = $row["first_name"] . " " . $row["last_name"];
									$fname = $row["first_name"];
									$body = "Check out " . $name . "'s profile on Tonality at https://gotonality.com/profile?id=" . $_SESSION["userID"];
									$subject = $name . " is on Tonality!";
                                ?>
                                <div class="text-block">
                                    <ul class="menu-vertical">
                                        <li>
                                            <a href="profile?id=<?php echo $viewed_profile; ?>">Profile</a>
                                        </li>
                                        <?php if (isset($_SESSION["userID"]) && $_SESSION["userID"] == $viewed_profile) { ?>
                                            <li>
                                                <a href="profile-notifications">Notifications</a>
                                            </li>
                                            <li>
                                                <a href="update-profile">Update Profile</a>
                                            </li>
                                        <?php } ?>
                                        <?php if (isset($_SESSION["userID"]) && $_SESSION["userID"] == $viewed_profile) { ?>
                                            <li>
                                                <a href="signout.php">Sign Out</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <p>Share this Profile</p>
                                    <div class="flex-cls">
                                        <span class="margin-5">
                                            <a href="mailto:?to=&body=<?php echo $body; ?>&subject=<?php echo $subject; ?>" style="text-decoration:none;">
                                                <div style="width:66px;height:20px;background-color:#b0b0b0;color:white;margin-top:3px;border-radius:3px;font-size:11px;font-family: Helvetica, Arial, sans-serif;" class="responsive">
													<svg style="float:left;margin-top:2px;margin-left:8px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
													  <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
													</svg>
													<p style="margin-top:-3px;float:left;margin-left:4px">Email</p>
												</div>
                                            </a>
                                        </span>
                                        <span class="margin-5">
                                            <a href="sms:?&body=<?php echo $body; ?>" style="text-decoration:none;">
                                                <div style="width:66px;height:20px;background-color:#5BC236;color:white;margin-top:3px;border-radius:3px;font-size:11px;font-family: Helvetica, Arial, sans-serif;" class="responsive">
													<svg style="float:left;margin-top:4px;margin-left:8px;" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
													  <path d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
													</svg>
													<p style="margin-top:-3px;float:left;margin-left:4px">SMS</p>
												</div>
                                            </a>
                                        </span>
                                    </div>
                                    <div class="flex-cls">
                                        <span class="margin-2">
                                            <div id="fb-root"></div>
                                            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0" nonce="TlbxFamd"></script>
                                            <div class="fb-share-button" data-href="https://gotonality.com/profile?id=<?php echo $viewed_profile ?>" data-layout="button" data-size="small">
                                                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse\" class="fb-xfbml-parse-ignore">
                                                Share</a>
                                            </div>
                                        </span>
                                        <span class="margin-7">
                                            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-text="<?php echo $body; ?>" data-hashtags="goTonality" data-lang="en" data-show-count="false">
                                            Tweet</a>
                                            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            $row = $usersqli->query("SELECT first_name, last_name FROM data WHERE userID=" . $_SESSION["userID"])->fetch_assoc();

							$songsRated = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_SESSION["userID"])->num_rows;
							$numComments = $usersqli->query("SELECT review_by, count(review_by) AS num FROM reviews WHERE reviews.review_by =" . $_SESSION['userID'])->fetch_assoc() ['num'];
							$following = $usersqli->query("SELECT follower_id FROM follows WHERE follower_id=" . $_SESSION["userID"])->num_rows;
							$followers = $usersqli->query("SELECT follower_id FROM follows WHERE following_id=" . $_SESSION["userID"])->num_rows;
                        ?>
                        <div class="col-lg-9">
                            <?php
                                $results = $usersqli->query("SELECT follows.followed_at AS datetime,
									follows.follower_id,
									NULL AS liked_review,
									NULL AS liked_by,
									NULL AS review_on,
									NULL AS post_id,
									NULL AS post_content,
									NULL AS post_topic,
									NULL AS post_by,
									NULL AS topic_id,
									NULL AS topic_subject,
									'follow' AS notif_type
								  FROM follows WHERE follows.following_id = ".$_SESSION['userID']."
							UNION
								SELECT likes.liked_at AS datetime,
									   NULL AS follower_id,
									   likes.liked_review,
									   likes.liked_by,
									   reviews.review_on,
									   NULL AS post_id,
									   NULL AS post_content,
									   NULL AS post_topic,
									   NULL AS post_by,
									   NULL AS topic_id,
									   NULL AS topic_subject,
									   'liked' AS notif_type
								  FROM likes 
								  LEFT JOIN reviews
								  ON reviews.review_id = likes.liked_review
								  WHERE review_by = ".$_SESSION["userID"]." 
							UNION
								SELECT posts.post_date AS datetime,
									   NULL AS follower_id,
									   NULL AS liked_review,
									   NULL AS liked_by,
									   NULL AS review_on,
									   posts.post_id,
									   posts.post_content,
									   posts.post_topic,
									   posts.post_by,
									   topics.topic_id,
									   topics.topic_subject,
									   'postReply' AS notif_type
								  FROM posts
								  LEFT JOIN topics
								  ON topics.topic_id = posts.post_topic
								  WHERE topics.topic_by = ".$_SESSION["userID"]." 
								ORDER BY datetime DESC");
								
								function humanTiming ($time){
								    $time = time() - $time - 3600;
								    $tokens = array (
								        31536000 => 'y',
								        2592000 => 'mo',
								        604800 => 'w',
								        86400 => 'd',
										3600 => 'h',
								        60 => 'm',
								        1 => 's'
								    );

								    foreach ($tokens as $unit => $text) {
								        if ($time < $unit) continue;
								        $numberOfUnits = floor($time / $unit);
								        return $numberOfUnits.$text;
								    }
								} 
								
                            ?>
                            <div class="boxed boxed--lg boxed--border">
                                <div id="gassed_songs" class="account-tab">                    
                                    <?php if($results->num_rows > 0){ ?>
                                    	<?php while($row = $results->fetch_assoc()){ ?>
                                    		<?php if($row['notif_type'] == 'follow' && $row['follower_id'] != $_SESSION['userID']){ 
                                    			$userinfo = $usersqli->query("SELECT * FROM data WHERE userID = ".$row['follower_id'])->fetch_assoc();
                                    		?>
                                    			<div class="row">
                                    				<div class="col-md-12">
                                    					<div class="boxed boxed--border">
					                                        <div class="row">
					                                            <div class="col-md-2" >
					                                                <img alt="avatar" src="<?php echo $userinfo['user_image']; ?>"/>
					                                            </div>
					                                            <div class="col-md-10">
					                                                <div class="row">
					                                                    <div class="col-md-6 text-left">
					                                                        <h5 class="mgb-0 clickable-row" data-href="profile?id=<?php echo $userinfo['userID']; ?>">@<?php echo $userinfo['user_name']; ?>  started following you
					                                                        </h5>
					                                                    </div>
					                                                    <div class="col-md-6 text-right">
					                                                        <span class="text-right"><?php echo humanTiming(strtotime($row['datetime'])) ?></span>
					                                                    </div>
					                                                </div>
					                                            </div>
					                                        </div>
					                                    </div>
                                    				</div>
                                    			</div>
                                    		<?php } else if($row['notif_type'] == 'liked' && $row['liked_by'] != $_SESSION['userID']){
                                    				$userinfo = $usersqli->query("SELECT * FROM data WHERE userID = ".$row['liked_by'])->fetch_assoc();
													$songinfo = $mysqli->query("SELECT spotify_uri FROM ratings WHERE songID = ".$row['review_on'])->fetch_assoc();
													
													$trackName = $api->getTrack($songinfo['spotify_uri'])->name;
                                    			?>
                                    			<div class="row">
                                    				<div class="col-md-12">
                                    					<div class="boxed boxed--border">
					                                        <div class="row">
					                                            <div class="col-md-2" >
					                                                <img alt="avatar" src="<?php echo $userinfo['user_image']; ?>"/>
					                                            </div>
					                                            <div class="col-md-10">
					                                                <div class="row">
					                                                    <div class="col-md-6 text-left">
					                                                        <h5 class="mgb-0 clickable-row" data-href="profile?id=<?php echo $userinfo['userID']; ?>">@<?php echo $userinfo['user_name']; ?>  liked your review on 
					        													<a href='song?id=<?php echo $songinfo['spotify_uri'] ?>'><?php echo $trackName ?></a>
					                                                        </h5>
					                                                    </div>
					                                                    <div class="col-md-6 text-right">
					                                                        <span class="text-right"><?php echo humanTiming(strtotime($row['datetime'])) ?></span>
					                                                    </div>
					                                                </div>
					                                            </div>
					                                        </div>
					                                    </div>
                                    				</div>
                                    			</div>
                                    		<?php } else if($row['notif_type'] == 'postReply' && $row['post_by'] != $_SESSION['userID']){
												$userinfo = $usersqli->query("SELECT * FROM data WHERE userID = ".$row['post_by'])->fetch_assoc();?>
												<div class="row">
                                    				<div class="col-md-12">
                                    					<div class="boxed boxed--border">
					                                        <div class="row">
					                                            <div class="col-md-2" >
					                                                <img alt="avatar" src="<?php echo $userinfo['user_image']; ?>"/>
					                                            </div>
					                                            <div class="col-md-10">
					                                                <div class="row">
					                                                    <div class="col-md-6 text-left">
					                                                        <h5 class="mgb-0 clickable-row" data-href="profile?id=<?php echo $userinfo['userID']; ?>">@<?php echo $userinfo['user_name']; ?>  left a reply on your topic: 
					              											<a href='topic?id=<?php echo $row['topic_id']; ?>'><?php echo $row['topic_subject'] ?></a>
					                                                        </h5>
					                                                    </div>
					                                                    <div class="col-md-6 text-right">
					                                                        <span class="text-right"><?php echo humanTiming(strtotime($row['datetime'])) ?></span>
					                                                    </div>
					                                                </div>
					                                            </div>
					                                        </div>
					                                    </div>
                                    				</div>
                                    			</div>
											<?php } ?>
	                                	<?php } ?>
                                    <?php } else { ?>
                                    	<p class="lead">No notifications to display</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
        </div>
        <iframe style="display:none;" name="target"></iframe>
        <?php
            include "footer.php";
            include "script.php";
        ?>
    </body>
</html>