<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
		<style>
		body{
			overflow: hidden;
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
    <body class="">
        <?php include "navbar.php"; ?>
        <?php
            include "db_userdata_connect.php";
            $songList = array();
            $sql = "SELECT first_name, last_name, user_image, user_name, user_level FROM data WHERE userID=" . $_GET["id"]; 
            $result = $usersqli->query($sql);
            $row = $result->fetch_assoc();
            $name = $row["first_name"] . " " . $row["last_name"];
            $viewed_profile = $_GET["id"];
            if (!isset($_SESSION)) {
                session_start();
            }
            
        ?>
		<div id="coverScreen"  class="LockOn">
			<img src="images/loading.gif" />
		</div>
        <div class="main-container">
            <section class="bg--secondary space--sm">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="boxed boxed--lg boxed--border" style="position:sticky;top:20px;">
                                <div class="text-block text-center">
                                    <img alt="avatar" src="<?php echo $row["user_image"];?>" />
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
									$songsRated = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_GET["id"])->num_rows;
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
									$numComments = $usersqli->query("SELECT review_by, count(review_by) AS num FROM reviews WHERE reviews.review_by =" . $_GET['id'])->fetch_assoc() ['num'];
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
                                    
                                    <?php

                                        if (isset($_SESSION["userID"]) && !($_SESSION["userID"] == $_GET["id"])) { ?>
                                        <?php
                                        echo "<br><br>";
											if ($usersqli->query("SELECT follower_id FROM follows WHERE follower_id=".$_SESSION['userID']." AND following_id=".$_GET["id"])->num_rows > 0) {
                                                ?>
                                                    <button id="follow-button-<?php echo $_GET["id"]; ?>" style="color: #fff;padding:4px 10px" class="btn btn--primary" data-follow="<?php echo $_GET["id"]; ?>" data-user="<?php echo $_SESSION["userID"] ?>">Unfollow</button>
												<?php } else { ?>
													<button id="follow-button-<?php echo $_GET["id"]; ?>" style="color: #fff;padding:4px 10px" class="btn btn--primary" data-follow="<?php echo $_GET["id"]; ?>" data-user="<?php echo $_SESSION["userID"] ?>">Follow</button>
                                                <?php } ?>
                                        <?php } ?>
                                    
                                </div>
                                <hr>
                                <?php
                                    $body = "Check out " . $name . "'s profile on Tonality at https://www.gotonality.com/profile?id=" . $_GET["id"];
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
                                        <li>
                                            <a href="#" data-toggle-class=".account-tab:not(.hidden);hidden|#gassed_songs;hidden" class="prof-scroll">Gassed Songs</a>
                                        </li>
                                        <li>
                                            <a href="#" data-toggle-class=".account-tab:not(.hidden);hidden|#trashed_songs;hidden" class="prof-scroll">Trashed Songs</a>
                                        </li>
                                        <li>
                                            <a href="#" data-toggle-class=".account-tab:not(.hidden);hidden|#all_reviews;hidden" class="prof-scroll">All Reviews</a>
                                        </li>
										<?php if (isset($_SESSION["userID"]) && isset($_SESSION["user_level"]) && $_SESSION["user_level"] >= 2 && $_SESSION["userID"] == $viewed_profile) { ?>
                                            <li>
                                                <a href="mod_dashboard.php">Moderator Dashboard</a>
                                            </li>
                                        <?php } ?>
										<?php if (isset($_SESSION["userID"]) && isset($_SESSION["user_level"]) && $_SESSION["user_level"] == 3 && $_SESSION["userID"] == $viewed_profile) { ?>
                                            <li>
                                                <a href="admin_dashboard.php">Admin Dashboard</a>
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
                            $fname = $row["first_name"];
                            

                            $row = $usersqli->query("SELECT first_name, last_name FROM data WHERE userID=" . $_GET["id"])->fetch_assoc();

                            $songsRated = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_GET["id"])->num_rows;
                            $numComments = $usersqli->query("SELECT review_by, count(review_by) AS num FROM reviews WHERE reviews.review_by =" . $_GET['id'])->fetch_assoc() ['num'];
                            $following = $usersqli->query("SELECT follower_id FROM follows WHERE follower_id=" . $_GET["id"])->num_rows;
                            $followers = $usersqli->query("SELECT follower_id FROM follows WHERE following_id=" . $_GET["id"])->num_rows;
                        ?>
                        <div class="col-lg-9">
                            <div class="boxed boxed--border">
                                <ul class="row row--list clearfix text-center">
                                    <li class="col-md-3 col-6">
                                        <a href="profile-followers?id=<?php echo $_GET["id"]; ?>">
                                            <span class="h6 type--uppercase type--fade">Followers</span>
                                            <span class="h3"><?php echo $followers; ?></span>
                                        </a>
                                    </li>
                                    <li class="col-md-3 col-6">
                                        <a href="profile-following?id=<?php echo $_GET["id"]; ?>">
                                            <span class="h6 type--uppercase type--fade">Following</span>
                                            <span class="h3"><?php echo $following; ?></span>
                                        </a>
                                    </li>
                                    <li class="col-md-3 col-6">
                                        <a href="#" data-toggle-class=".account-tab:not(.hidden);hidden|#gassed_songs;hidden">
                                            <span class="h6 type--uppercase type--fade">Votes</span>
                                            <span class="h3"><?php echo $songsRated; ?></span>
                                        </a>
                                    </li>
                                    <li class="col-md-3 col-6">
                                        <a href="#" data-toggle-class=".account-tab:not(.hidden);hidden|#all_reviews;hidden">
                                            <span class="h6 type--uppercase type--fade">Reviews</span>
                                            <span class="h3"><?php echo $numComments; ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

<!------------------ PINS -------------------------->
							<style type="text/css">
								.is-hidden {
									display: none;
								}
							</style>
							<?php 
								$pins = $usersqli->query("SELECT * FROM pins WHERE user_id=".$_GET['id']);
								if($pins->num_rows >  4){ ?>
								<div class="boxed boxed--border" style="margin-top:-20px;margin-bottom:10px">
									<section class="padding-bottom-0">
										<div class="container">
											<div class="row">
												<h4 style="margin-top:-60px"><?php echo $name . "'s Pins" ?></h4>
												<div class="col-md-12 col-12">
													<div class="slider slider--columns is-hidden" data-arrows="false" data-paging="true" style="margin-bottom:-60px">

														<ul class="slides">	
															<?php
															$index = 0;
															while($row = $pins->fetch_assoc()){
																$index++;
															?>
															<li class="col-md-3 col-6">
																<?php 
											
																if($row['pin_type'] == 'track'){ 
																	$track = $api->getTrack($row['pin_item_id']); ?>
																	<a class="clickable-row" data-href="song?id=<?php echo $row['pin_item_id']  ?>">
																		<img alt="Cover Image" src="<?php echo $track->album->images[0]->url; ?>" />
																	</a>
																	<p style="font-size:12px"><?php echo $track->name; ?></p>
																<?php }else if($row['pin_type'] == 'artist'){ 
																	$artist = $api->getArtist($row['pin_item_id']); ?>
																	<a class="clickable-row" data-href="artist?id=<?php echo $row['pin_item_id']  ?>">
																		<img alt="Cover Image" src="<?php echo $artist->images[0]->url; ?>" />
																	</a>
																	<p style="font-size:12px"><?php echo $artist->name; ?></p>
																 <?php }else if($row['pin_type'] == 'album'){ 
																	$album = $api->getAlbum($row['pin_item_id']); ?>
																	<a class="clickable-row" data-href="album?id=<?php echo $row['pin_item_id']  ?>">
																		<img alt="Cover Image" src="<?php echo $album->images[0]->url; ?>" />
																	</a>
																	<p style="font-size:12px"><?php echo $album->name; ?></p>
																<?php
																}
																?>
															</li>
															<?php 
															}?>
														</ul>
													</div>
												</div>
												<!--end of col-->
											</div>
											<!--end row-->
										</div>
										<!--end of container-->
									</section>
								</div>
                            <?php } else if($pins->num_rows >0){ ?>
								<div class="boxed boxed--border" style="margin-top:-20px;margin-bottom:10px">
									<h4><?php echo $name . "'s Pins" ?></h4>
									<ul class="row row--list clearfix text-center">
							<?php
									while($row = $pins->fetch_assoc()){
							?>
										<li class="col-md-3 col-6">
											<?php 
											
											if($row['pin_type'] == 'track'){
												$track = $api->getTrack($row['pin_item_id']);
												echo "<a href='song?id=".$row['pin_item_id']."'><img src='" . $track->album->images[0]->url . "' style='width:100px;height:100px;' />";
												echo '<span class="h6" style="margin-top:-15px">' . $track->name . '</span></a>';
											}else if($row['pin_type'] == 'artist'){
												$artist = $api->getArtist($row['pin_item_id']);
												echo "<a href='artist?id=".$row['pin_item_id']."'><img src='" . $artist->images[0]->url . "' style='width:100px;height:100px;' />";
												echo '<span class="h6" style="margin-top:-15px">' . $artist->name . '</span></a>';
											}else if($row['pin_type'] == 'album'){
												$album = $api->getAlbum($row['pin_item_id']);
												echo "<a href='album?id=".$row['pin_item_id']."'><img src='" . $album->images[0]->url . "' style='width:100px;height:100px;' />";
												echo '<span class="h6" style="margin-top:-15px">' . $album->name . '</span></a>';
											}
											
											?>
										</li>
									<?php } ?>	
                                </ul>
                            </div>
						<?php } ?>
<!----------------- END PINS kek W ----------------------->
                            <?php
                                require_once "rating_system.php";
                                include "api_connect.php";
                                include "db_songs_connect.php";
                                $gassedListResults = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_GET["id"] . " AND vote_type=True ORDER BY voted_at DESC LIMIT 0, 50");
								echo "<span style='display:none' id='num_gas_results'>50</span>";
								echo "<span style='display:none' id='profile-id'>".$_GET['id']."</span>";
                                $gassedList = array();
                                while($row = $gassedListResults->fetch_assoc()){
                                    array_push($gassedList, $row['song_id']);
                                }
								$index = 0;
                            ?>
                            <div class="boxed boxed--lg boxed--border " id="display-area">
                                <div id="gassed_songs" class="account-tab">
                                    <h4><?php echo $name; ?>'s Gassed Songs</h4>
                                    <?php if (!empty($gassedList)) { 
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
                                        
                                    ?>
                                        <table>
                                            <tbody>
                                                <?php
                                                    foreach ($tracks->tracks as $track) {
                                                    $spotifyID = $spotifyIDs[$index];
                                                    array_push($songList, $spotifyID);
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
                                                        <td class="clickable-row <?php if(($index+1)%40 == 0){ echo " gassed-trigger"; } ?>" data-href="song?id=<?php echo $spotifyID; ?>">
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
                                            </tbody>
                                        </table>
										
                                    <?php }  else { ?>
                                        <p class="lead">This user hasn't gassed any songs</p>
                                    <?php } 
                                    unset($spotifyIDs);
                                    unset($songIDs);
                                    unset($posRatings);
                                    unset($totRatings);
                                    $trashedListResults = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $_GET["id"] . " AND vote_type=False ORDER BY voted_at DESC LIMIT 0, 50");
									echo "<span style='display:none' id='num_trash_results'>50</span>";
									
                                    $trashedList = array();
                                    while($row = $trashedListResults->fetch_assoc()){
                                        array_push($trashedList, $row['song_id']);
                                    }
                                    ?>
                                </div>
                                <div id="trashed_songs" class="hidden account-tab">
                                    <h4><?php echo $name; ?>'s Trashed Songs</h4>
                                    <?php if (!empty($trashedList)) {
                                        $songs = array();
                                        foreach ($trashedList as $songID) {
                                            $results = $mysqli->query("SELECT spotify_uri, song_pos_ratings, song_total_ratings FROM ratings WHERE songID=$songID");
                                            $row = $results->fetch_assoc();
                                            array_push($songs, $row['spotify_uri']);
                                            $spotifyIDs[] = $row['spotify_uri'];
                                            $songIDs[] = trim($songID);
                                            $posRatings[] = $row["song_pos_ratings"];
                                            $totRatings[] = $row["song_total_ratings"];
                                        }

                                        $tracks = $api->getTracks($songs);
                                        $index = 0;
                                    ?>
                                        <table>
                                            <tbody>
                                                <?php
                                                    foreach ($tracks->tracks as $track) {
                                                    $spotifyID = $spotifyIDs[$index];
                                                    array_push($songList, $spotifyID);
                                                    $trackName = $track->name;
                                                    $trackArtist = $track->artists[0]->name;
                                                    $songID = $songIDs[$index];
                                                    $posRating = $posRatings[$index];
                                                    $totRating = $totRatings[$index];
                                                    $popularity = $track->popularity;
                                                    $cover = $track->album->images[0]->url;

                                                    $index++;
                                                ?>
                                                    <tr class="<?php if(($index+1)%40 == 0){ echo " trashed-trigger"; } ?>">
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
												<tr class="more-trashed-content">
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php }  else { ?>
                                        <p class="lead">This user hasn't trashed any songs</p>
                                    <?php } 
                                    ?>
                                </div>
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
                                            data.userID = " . $_GET["id"] . " ORDER BY
                                            reviews.review_likes DESC,
                                            reviews.review_date DESC
										LIMIT 0, 50";
                                $result = $usersqli->query($sql);
								echo "<span style='display:none' id='num_review_results'>50</span>";
                                ?>
                                <div id="all_reviews" class="hidden account-tab">
                                    <h4><?php echo $name; ?>'s Reviews</h4>
                                
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
										$spotifyID = $spotifyIDs[$index];
										
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
                                <?php } else {?>
                                    <p class="lead">User has no reviews</p>
                                <?php } 
                                $comment_index = 0;
                                if(!isset($_SESSION)){
                                    session_start();
                                }
                                unset($_SESSION['songlist']);
                                $_SESSION['songlist'] = $songList;

                                $usersqli->close();
                                $mysqli->close();
                                ?>
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
		<script async>
		$(".prof-scroll").click(function() {
			$('html, body').animate({
				scrollTop: $("#display-area").offset().top
			}, 1000);
		});

		$(window).on('beforeunload', function(){
		  window.scrollTo({top: 0});
		  $("#coverScreen").show();
		});
		
		$(window).on('load', function () {
		$("#coverScreen").hide();
		document.getElementsByTagName('body')[0].style.overflow = 'visible';
		});
		
		$('.btn').on('click', function(){
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
		
		$('.like-button').on('click', function(){
			var buttonId = $(this).attr('id');
			var button = document.getElementById(buttonId);
			var likeCount = document.getElementById('num-likes-' + button.getAttribute('data-revid'));
			if(button.getAttribute('data-type') == 'unliked'){
				$.ajax({
					type: "POST",
					url: "add_liked_review.php",
					data: { 'review':button.getAttribute('data-revid'), 'profile':button.getAttribute('data-prof') }
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
					data: { 'review':button.getAttribute('data-revid'), 'profile':button.getAttribute('data-prof') }
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
				button.style = "color:orange;float:right;margin-right:-4px";
			});
		});
		function elementInView(elem){
		    var top_of_element = $(elem).offset().top;
			var bottom_of_element = $(elem).offset().top + $(elem).outerHeight();
			var bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
			var top_of_screen = $(window).scrollTop();

			return (bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element);
		};
		$(window).scroll(function(){
			if (elementInView($('.gassed-trigger'))){
				$('.gassed-trigger').removeClass("gassed-trigger");
				console.log('load more gassed content');
				$.ajax({
					type: "POST",
					url: "load_more_gassed.php",
					data: { 'start_num':document.getElementById('num_gas_results').innerHTML, 'profile-id':document.getElementById('profile-id').innerHTML }
				}).done(function(content){
					console.log(content);
					$('.more-gassed-content').replaceWith(content);
					console.log("CONTENT LOADED");
					document.getElementById('num_gas_results').innerHTML = parseInt(document.getElementById('num_gas_results').innerHTML) + 50;
				});
				
			}
			if (elementInView($('.trashed-trigger'))){
				$('.trashed-trigger').removeClass("trashed-trigger");
				console.log('load more trashed content');
				$.ajax({
					type: "POST",
					url: "load_more_trashed.php",
					data: { 'start_num':document.getElementById('num_trash_results').innerHTML, 'profile-id':document.getElementById('profile-id').innerHTML }
				}).done(function(content){
					console.log(content);
					$('.more-trashed-content').replaceWith(content);
					console.log("CONTENT LOADED");
					document.getElementById('num_trash_results').innerHTML = parseInt(document.getElementById('num_trash_results').innerHTML) + 50;
				});
				
			}
			if (elementInView($('.review-trigger'))){
				$('.review-trigger').removeClass("review-trigger");
				console.log('load more review content');
				$.ajax({
					type: "POST",
					url: "load_more_reviews.php",
					data: { 'start_num':document.getElementById('num_review_results').innerHTML, 'profile-id':document.getElementById('profile-id').innerHTML }
				}).done(function(content){
					console.log(content);
					$('.more-review-content').replaceWith(content);
					console.log("CONTENT LOADED");
					document.getElementById('num_review_results').innerHTML = parseInt(document.getElementById('num_review_results').innerHTML) + 50;
				});
				
			}
		});
		</script>
    </body>
</html>