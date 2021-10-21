<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
		<style>
		.tooltip2 {
		  position: relative;
		  display: inline-block;
		  top:20%;
		}

		.tooltip2 .tooltiptext {
		  visibility: hidden;
		  width: 200px;
		  background-color: black;
		  color: #fff;
		  text-align: center;
		  border-radius: 6px;
		  padding: 5px 0;
		  position: absolute;
		  z-index: 1;
		  bottom: 150%;
		  left: 50%;
		  margin-left: -180px;
		}

		.tooltip2 .tooltiptext::after {
		  content: "";
		  position: absolute;
		  top: 100%;
		  left: 50%;
		  margin-left: 75px;
		  border-width: 5px;
		  border-style: solid;
		  border-color: black transparent transparent transparent;
		}

		.tooltip2:hover .tooltiptext {
		  visibility: visible;
		}
		</style>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <?php
            include "db_userdata_connect.php";
            include "db_userdata_connect.php";
            $sql = "SELECT * FROM data WHERE userID=" . $_SESSION["userID"];
            $result = $usersqli->query($sql);

            $row = $result->fetch_assoc();
            $name = $row["first_name"] . " " . $row["last_name"];
            $viewed_profile = $_SESSION["userID"];
            
        ?>
        <div class="main-container">
            <?php
                if (isset($_GET["error"])) {
                    if ($_GET["error"] == "user") {
                        echo "<div class='p-3 mb-2 bg-danger text-white text-center'>That username is already taken</div>";
                    }
                    else if ($_GET["error"] == "email") {
                        echo "<div class='p-3 mb-2 bg-danger text-white text-center'>That email is already linked to an account</div>";
                    }
                }
            ?>
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
                                    $body = "Check out " . $name . "'s profile on Tonality at http://gotonality.com/profile?id=" . $_SESSION["userID"];
                                    $subject = $name . " on VRS!";
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
                        
                        <div class="col-lg-9">
                            <?php
                                $row = $usersqli->query("SELECT first_name, last_name, user_name, user_image, email, userID FROM data WHERE userID=" . $_SESSION["userID"])->fetch_assoc();
                                $name = $row["first_name"] . " " . $row["last_name"];
                                $fname = $row["first_name"];
                                $lname = $row["last_name"];
                                $uname = $row["user_name"];
                                $email = $row["email"];
                                $userID = $row['userID'];
                                $userimg = $row["user_image"];
                            ?>
                            <div class="boxed boxed--lg boxed--border">
                                <div id="gassed_songs" class="account-tab">
                                    <h4>Update Profile</h4>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="boxed boxed--lg boxed--border">
                                                <form  class="row mx-0 " action="do_update_profile" method="post" enctype="multipart/form-data">
                                                    <div class="col-md-6">
                                                        <span>First Name</span>
                                                        <input type="text" id="first" name="first" oninput="updatePreview();valueChanged()" value="<?php echo $fname ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span>Last Name</span>
                                                        <input type="text" id="last" name="last"  oninput="updatePreview();valueChanged()" value="<?php echo $lname ?>" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <span>Username</span>
                                                        <input type="text" id="user" name="user" placeholder="Username" oninput="userRequirements(this); updatePreview();valueChanged()" value="<?php echo $uname ?>" required>
                                                        <p id="userp" style="display:none">Username must be <span id="ulen">between 4-20 characters</span> and <span id="uchars">cannot contain special characters</span>
                                                        </p>
                                                    </div>
													<div class="col-md-11 col-lg-11 col-11">
														<input type="text" name="img" oninput="changeAction(this);" placeholder="Profile Image Link">
													</div>
													<div class="col-lg-1 col-md-1 col-1 text-center">
														<div class="tooltip2">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16" style="background:none">
															  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
															  <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
															</svg>
														  <span class="tooltiptext">Search for an image on Google and get the image address, or you can upload your own using Imgur.com</span>
														</div>
													</div>
                                                    <div class="col-md-12" style="">
                                                        <span>Email</span>
                                                        <input type="text" id="email" name="email"  oninput="validEmail(this);valueChanged()" value="<?php echo $email ?>" required><br>
                                                        <p id="emailp" style="display:none">Enter a valid email</p>
                                                    </div>
                                                    
                                                    <div class="col-md-12 boxed text-center">
                                                        <button type="submit" disabled="true" id="submit" style="color:#fff;background:#d1e8e4" class="btn btn--primary type--uppercase">Update Info</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="boxed boxed--lg boxed--border">
                                                <div class="text-block text-center">
                                                    <img id="preview" src="<?php echo $userimg ?>" class="image--md" style="max-height: 15.428571em;" />
                                                    <h3 id="name"><?php echo $fname.' '. $lname ?></h3>
                                                    <span class="h5" id="username" style="margin-top:-10px">@<?php echo $uname ?></span>
                                                    
                                                </div>
                                            </div>
                                            <div class="">
												<div class="text-block text-center">
													<a href="reset" style="color: #fff;" class="btn btn--primary type--uppercase">Reset Password</a>
												</div>
                                            </div>
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
        <?php
        echo "<script type=\"text/javascript\">
                valueChanged = function(){
                    if(document.getElementById('first').value != \"$fname\"){
                        document.getElementById(\"submit\").style = \"background-color:#61ba9e;\";
                        document.getElementById(\"submit\").disabled = false;
                    }else if(document.getElementById('last').value != \"$lname\"){
                        document.getElementById(\"submit\").style = \"background-color:#61ba9e;\";
                        document.getElementById(\"submit\").disabled = false;
                    }else if(document.getElementById('user').value != \"$uname\"){
                        document.getElementById(\"submit\").style = \"background-color:#61ba9e;\";
                        document.getElementById(\"submit\").disabled = false;
                    }else if(document.getElementById('email').value != \"$email\"){
                        document.getElementById(\"submit\").style = \"background-color:#61ba9e;\";
                        document.getElementById(\"submit\").disabled = false;
                    }else if(document.getElementById(\"img\").value != \"$userimg\"){
                        document.getElementById(\"submit\").style = \"background-color:#61ba9e;\";
                        document.getElementById(\"submit\").disabled = false;
                    }else{
                        document.getElementById(\"submit\").style = \"background-color:#d1e8e4;\";
                        document.getElementById(\"submit\").disabled = true;
                    }
                }
            </script>"; 
        ?>
        <script type="text/javascript">
            changeAction = function(link){
               if(link.value == ""){
                  document.getElementById("preview").src = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png";
               }else{
                   document.getElementById("preview").src = link.value;
               }
            }

            let specialChars = ["+", "-", "&", "|", "!", "(", ")", "#", "{", "}", "[", "]", "^", "~", "*", "?", ":", "@", "$", "%", "_", "=", "/", "\\", "?", "\'"];
            let upperChars = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
            let lowerChars = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "y", "v", "w", "x", "y", "z"];
            var passwordMet = false;
            var userMet = false;
            //var imgMet = false;
            updatePreview = function(){
                if(document.getElementById("first").value != "" || document.getElementById("last").value != ""){
                    let name = document.getElementById("first").value + " " + document.getElementById("last").value;
                    document.getElementById("name").innerHTML = name;
                }
                if(document.getElementById("user").value != ""){
                    let user = "@" + document.getElementById("user").value;
                    document.getElementById("username").innerHTML = user;
                }
            }
            userRequirements = function(select){
                document.getElementById("userp").style = "";
                var user = select.value;
                if (specialChars.some(v => user.includes(v))){
                    document.getElementById("uchars").style = "color:red";
                }else{
                    document.getElementById("uchars").style = "color:green";
                }
                if (user.length < 4 || user.length > 20){
                    document.getElementById("ulen").style = "color:red";
                }else{
                    document.getElementById("ulen").style = "color:green";
                }
                
                if(!(specialChars.some(v => user.includes(v))) && !(user.length < 4 || user.length > 20)){
                    userMet = true;
                    }
                    else{
                        userMet = false;
                    }
                if(passwordMet && userMet){
                    document.getElementById("submit").disabled = false;
                }else{
                    document.getElementById("submit").disabled = true;
                }
            }
            function validateEmail(email) {
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }
            validEmail = function(select){
                var email = select.value;
                if(!validateEmail(email)){
                    document.getElementById("emailp").style = "color:red";
                }else{
                    document.getElementById("emailp").style = "display:none";
                }
            }
        </script>
    </body>
</html>