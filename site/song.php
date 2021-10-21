<?php
require_once "rating_system.php";
include "db_songs_connect.php";
include "db_userdata_connect.php";
include "api_connect.php";

if (!isset($_SESSION)) {
    session_start();
}

$spotifyID = $_GET["id"];
$_SESSION['current_song_spotifyid'] = $spotifyID;
$result = $mysqli->query("SELECT songID FROM ratings WHERE spotify_uri='" . $spotifyID . "'");
if ($result->num_rows < 1) {
    $mysqli->query("INSERT INTO `ratings`(`songID`, `spotify_uri`, `song_pos_ratings`, `song_total_ratings`, `pos_ratings_this_month`, `ratings_this_month`) VALUES (NULL, '" . $spotifyID . "', 0, 0, 0, 0)");
}
$result = $mysqli->query("SELECT songID, song_pos_ratings, song_total_ratings FROM ratings WHERE spotify_uri='$spotifyID'");
$row = $result->fetch_assoc();
$songID = $row["songID"];
$_SESSION["current_song"] = $songID;

$track = $api->getTrack($spotifyID, ['market'=>'US']);

$trackArtist = $track->artists[0]->name;
$trackName = $track->name;
$cover = $track->album->images[0]->url;
$posRating = $row["song_pos_ratings"];
$totRatings = $row["song_total_ratings"];
$pop = $track->popularity;
$albumID = $track->album->id;
$artistID = $track->artists[0]->id;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <div class="main-container">
            <section class="bg--secondary space--sm">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="boxed boxed--lg boxed--border">
                                <div class="text-block text-center">
                                    <div class="row images-box">
                                        <?php 
                                            
                                            if (isset($_SESSION["userID"])) {
                                                include "db_userdata_connect.php";
                                                $voterTrashed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=False AND song_id = $songID")->num_rows > 0;
                                                $voterGassed = $usersqli->query("SELECT vote_by FROM user_votes WHERE vote_by=" . $_SESSION["userID"] . " AND vote_type=True AND song_id = $songID")->num_rows > 0;
                                                echo '<div class="inline-block">'; 
                                                if ($voterGassed) {
                                                    echo "<a href=\"add_rating.php?rating=gas\" target=\"targetMain\"><img height='90' src=\"images/gas-voted.png\" id=\"gasIcon\" onclick=\"gasMainVoteClicked()\"/></a>";
                                                }
                                                else {
                                                    echo "<a href=\"add_rating.php?rating=gas\" target=\"targetMain\"><img height='90' src=\"images/gas-vote.png\" id=\"gasIcon\" onclick=\"gasMainVoteClicked()\"/></a>";
                                                }
                                                if ($voterTrashed) {
                                                    echo "<a href=\"add_rating.php?rating=trash\" target=\"targetMain\"><img height='90' src=\"images/trash-voted.png\" id=\"trashIcon\" onclick=\"trashMainVoteClicked()\"/></a>";
                                                }
                                                else {
                                                    echo "<a href=\"add_rating.php?rating=trash\" target=\"targetMain\"><img height='90' src=\"images/trash-vote.png\" id=\"trashIcon\"  onclick=\"trashMainVoteClicked()\"/></a>";
                                                }
                                                echo '</div>'; 

                                            }
                                            else {
                                                echo '<div class="inline-block"><a data-tooltip="Signin" href="login"><img data-tooltip="Signin" src="images/gas-novote.png" class="img-responsive" height="90"  /></a>
                                                    <a data-tooltip="Signin" href="login"><img data-tooltip="Signin" src="images/trash-novote.png" class="img-responsive" height="90"  /></a></div>
                                                ';
                                            }
                                        ?>
                                        <div class="image-2">
                                            <a href="album?id=<?php echo $albumID; ?>"><img alt="avatar" src="<?php echo $cover; ?>" class="image--md" style="max-height: 15.428571em;" /></a>
                                        </div>
                                    </div>
                                    <span class="h2 mgb-5">
                                        <?php 
                                            if (get_rating($posRating, $totRatings, $pop) >= 60) {
                                                echo '<img data-tooltip="Signin" src="images/gas-icon.png" class="img-responsive" height="50"  />'.get_rating($posRating, $totRatings, $pop).'%';
                                            }
                                            elseif (get_rating($posRating, $totRatings, $pop) <= 60) {
                                                echo '<img src="images/trash-icon.png" class="img-responsive" height="30"  />'.get_rating($posRating, $totRatings, $pop).'%';
                                            }
                                        ?>
                                    </span>
                                    <span class="h2 mgb-5"><?php echo $trackName; ?></span>
                                    <a href="artist?id=<?php echo $artistID; ?>" style="color: #000;font-style:italic" class="h4 mgb-5"><?php echo $trackArtist; ?></a><br>
                                    <?php  
                                        $body = "Check out " . $trackName . " by " . $trackArtist . " on Tonality! It currently has " . get_rating($posRating, $totRatings, $pop) . "% gas! You can leave your rating and review here: https://gotonality.com/song?id=" . $spotifyID;
                                        $subject = $trackName . " is on Tonality!"; 
                                    ?>
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
                                        <span class="margin-2">
                                            <div id="fb-root"></div>
                                            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0" nonce="TlbxFamd"></script>
                                            <div class="fb-share-button" data-href="https://gotonality.com/song?id=<?php echo $spotifyID ?>" data-layout="button" data-size="small">
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
                                    <?php
                                        if(isset($_SESSION['songlist'])){
                                            $songlist = $_SESSION['songlist'];
                                            $index = array_search($spotifyID, $songlist);
                                            if(isset($songlist[$index-1])){
                                                echo "<a href='song?id=".$songlist[$index-1]."'><button class='left-button'><</button></a>";
                                            }
                                            if(isset($songlist[$index+1])){
                                            echo "<a href='song?id=".$songlist[$index+1]."'><button class='right-button'>></button></a>";
                                            }
                                        }
                                    ?>
									<?php if (isset($_SESSION['userID'])) {
											$pins = $usersqli->query("SELECT * FROM pins WHERE user_id=".$_SESSION['userID']);
											$results = $usersqli->query("SELECT user_id FROM pins WHERE user_id=" . $_SESSION['userID'] . " AND pin_item_id='" . $spotifyID ."'");
											if($pins->num_rows == 12 && $results->num_rows > 0){ ?>
												<a data-tooltip="Toggle Pin to Profile"><svg id="pin" class="pin-button" xmlns="http://www.w3.org/2000/svg" style="color:red" data-type="pinned" data-itemid="<?php echo $spotifyID ?>" width="24" height="24" fill="currentColor" class="bi bi-pin-angle-fill" viewBox="0 0 16 16">
												  <path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z"/>
												</svg></a>
											<?php
											} else if($pins->num_rows == 12) { ?>
												<a data-tooltip="You have reached max pins"><svg id="pin" class="pin-button" xmlns="http://www.w3.org/2000/svg" style="color:#c7c7c7" data-type="none" data-itemid="<?php echo $spotifyID ?>" width="24" height="24" fill="currentColor" class="bi bi-pin-angle-fill" viewBox="0 0 16 16">
												  <path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z"/>
												</svg></a>
											<?php
											} else if ($results->num_rows > 0) { ?>
												<a data-tooltip="Toggle Pin to Profile"><svg id="pin" class="pin-button" xmlns="http://www.w3.org/2000/svg" style="color:red" data-type="pinned" data-itemid="<?php echo $spotifyID ?>" width="24" height="24" fill="currentColor" class="bi bi-pin-angle-fill" viewBox="0 0 16 16">
												  <path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z"/>
												</svg></a>
											<?php } else { ?>
												<a data-tooltip="Toggle Pin to Profile"><svg id="pin" class="pin-button" xmlns="http://www.w3.org/2000/svg" data-type="unpinned" data-itemid="<?php echo $spotifyID ?>" width="24" height="24" fill="currentColor" class="bi bi-pin-angle" viewBox="0 0 16 16">
												  <path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146zm.122 2.112v-.002zm0-.002v.002a.5.5 0 0 1-.122.51L6.293 6.878a.5.5 0 0 1-.511.12H5.78l-.014-.004a4.507 4.507 0 0 0-.288-.076 4.922 4.922 0 0 0-.765-.116c-.422-.028-.836.008-1.175.15l5.51 5.509c.141-.34.177-.753.149-1.175a4.924 4.924 0 0 0-.192-1.054l-.004-.013v-.001a.5.5 0 0 1 .12-.512l3.536-3.535a.5.5 0 0 1 .532-.115l.096.022c.087.017.208.034.344.034.114 0 .23-.011.343-.04L9.927 2.028c-.029.113-.04.23-.04.343a1.779 1.779 0 0 0 .062.46z"/>
												</svg></a>
											<?php } 
										} ?>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-lg-12" style="border-bottom:1px solid #ececec;border-top:1px solid #ececec;margin-bottom:6px">
								<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
								<!-- Horizontal Res Unit -->
								<ins class="adsbygoogle"
									 style="display:block"
									 data-ad-client="ca-pub-3114841394059389"
									 data-ad-slot="9248316743"
									 data-ad-format="auto"
									 data-full-width-responsive="true"></ins>
								<script>
									 (adsbygoogle = window.adsbygoogle || []).push({});
								</script>
								<span>Sponsored</span>
						</div>
					</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="boxed boxed--lg boxed--border">
                                <div class="row">
                                    

                                    <div class="col-md-6 margin-35">
										
										<?php
										if(isset($_SESSION['spotify_connected']) && $_SESSION['spotify_connected'] && $_SESSION['isPremium']){
											echo "<a target=\"target\" href=\"playbackFunctions/queue.php?id=".$spotifyID."\" onclick='showAlert()' style='color:black'><button class='btn btn--primary' style='width:fit-content;display:inline-block;margin-bottom:20px;padding:10px;font-size:14px;'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"currentColor\" class=\"bi bi-music-note\" viewBox=\"0 0 16 16\">
										  <path d=\"M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2z\"/>
										  <path fill-rule=\"evenodd\" d=\"M9 3v10H8V3h1z\"/>
										  <path d=\"M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5V2.82z\"/>
											</svg>
											<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"18\" fill=\"currentColor\" class=\"bi bi-plus\" viewBox=\"0 0 16 16\" style='margin-left:-13px;margin-bottom:-5px;'>
										<path d=\"M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z\"/>
										</svg>  Queue</button></a>";
											echo '<style>.alert{position:fixed;min-width:180px;z-index:1;top:100px;background-color:#74e38c;border-radius:5px;width:20%;height:40px;border:2px solid #6dad7b;color:white;left:40%;}</style><div class="alert alert-success" id="success-alert" style="display:none"><p style="width:fit-content;margin:-14px auto;font-size:14px;padding:5px;">Successfully Queued</p></div>';
											echo '<script>
											function showAlert(){
												document.getElementById("success-alert").style = "display:block";
												setTimeout(function() { document.getElementById("success-alert").style = "display:none"; }, 3000);
											}
											</script>';
										}
										?>
                                        <iframe style="min-height: unset;" height="80px" src="https://open.spotify.com/embed/track/<?php echo $spotifyID; ?>" width="300" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                                    </div>
									<div class="col-md-6">
                                        <div class="comments-form">
                                            <h4>Leave a review:</h4>
                                            <form class="row" action="add_comment.php" method="post">
                                                <div class="col-md-12">
                                                    <textarea id="comment" name="comment" cols="100" rows="6" placeholder="Add your Review..." oninput="commentCheck()" style="border:1px solid gray"></textarea>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="btn btn--primary" type="submit">Submit</button>
                                                </div>
                                                <?php
                                                    if (isset($_GET["error"]) && $_GET["error"] == "signin") {
                                                        echo "<span> You need to <a href=\"login\" style=\"color:red\">log in</a> to do that</span>";
                                                    }
                                                ?>                                       
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        include "display_similar_songs.php";
                        include "display_comments.php";
                    ?>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
            <iframe style="display:none;" name="targetMain"></iframe>
        </div>
        <?php
            include "footer.php";
            include "script.php";
        ?>
		<script src="https://cdn.tiny.cloud/1/40hct26l99thitiqzooduhp5jwcna4y5o9mgb5axydeeiom6/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
		<script>
		tinymce.init({
		  selector: '#comment',
		  height: 250,
		  skin: 'outside',
		  icons: 'thin',
		  toolbar_location: 'bottom',
		  menubar: false,
		  plugins: [
			'advlist autolink lists link charmap print preview anchor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table paste imagetools wordcount'
		  ],
		  toolbar: 'styleselect | bold italic link underline strikethrough | numlist bullist | preview fullscreen', 
		  placeholder: 'Add your Review...'
		});

	$('.pin-button').on('click', function(){
		var button = document.getElementById('pin');
		if(button.getAttribute('data-type') == 'unpinned'){
			$.ajax({
				type: "POST",
				url: "add_pin.php",
				data: { 'item_id':button.getAttribute('data-itemid'), 'item_type':"track" }
			}).done(function(){
				button.innerHTML = '<path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z"/>';
				button.style = "color:red";
				button.setAttribute('data-type', 'pinned');
			});
		}
		if(button.getAttribute('data-type') == 'pinned'){
			$.ajax({
				type: "POST",
				url: "delete_pin.php",
				data: { 'item_id':button.getAttribute('data-itemid'), 'item_type':"track" }
			}).done(function(){
				button.innerHTML = '<path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146zm.122 2.112v-.002zm0-.002v.002a.5.5 0 0 1-.122.51L6.293 6.878a.5.5 0 0 1-.511.12H5.78l-.014-.004a4.507 4.507 0 0 0-.288-.076 4.922 4.922 0 0 0-.765-.116c-.422-.028-.836.008-1.175.15l5.51 5.509c.141-.34.177-.753.149-1.175a4.924 4.924 0 0 0-.192-1.054l-.004-.013v-.001a.5.5 0 0 1 .12-.512l3.536-3.535a.5.5 0 0 1 .532-.115l.096.022c.087.017.208.034.344.034.114 0 .23-.011.343-.04L9.927 2.028c-.029.113-.04.23-.04.343a1.779 1.779 0 0 0 .062.46z"/>';
				button.style = "";
				button.setAttribute('data-type', 'unpinned');
			});
		}
	});
	    </script>
    </body>
</html>