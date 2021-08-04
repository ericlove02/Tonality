<?php
if(!isset($_SESSION)){
	session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
		<style>
		.accordion {
		  background-color: #eee;
		  color: #444;
		  cursor: pointer;
		  padding: 18px;
		  width: 100%;
		  border: none;
		  text-align: left;
		  outline: none;
		  font-size: 20px;
		  transition: 0.4s;
		  margin: 5px;
		  border-radius: 5px;
		}
		.accordion p{
			width: 90%;
			float:left;
		}
		.active, .accordion:hover {
		  background-color: #ccc;
		}

		.accordion:after {
		  content: '\002B';
		  font-size: 30px;
		  width: 5%;
		  color: #777;
		  font-weight: bold;
		  float: right;
		  margin-left: 5px;
		}

		.active:after {
		  content: "\2212";
		  font-size: 30px;
		  float: right;
		  width: 5%;
		}

		.panel {
		  padding: 0 18px;
		  background-color: white;
		  max-height: 0;
		  overflow: hidden;
		  transition: max-height 0.2s ease-out, border .2s ease-in;
		  width: 80%;
		  margin: 0 5%;
		}
		</style>
    </head>

<body>
<?php include "navbar.php"; ?>
<div class="main-container">
    <div class="container">
		<h2 style="margin-top:20px;">Tonality Help and Popular FAQs</h2>
		<button class="accordion"><p>What is Tonality?</p></button>
		<div class="panel">
		<p>Tonality is an online multimedia user experience where you can engage directly with the music you love. You can rate songs, discuss with other music lovers, and discover new music. To find more information about how to use Tonality, check out the rest of this page. To learn more about our team, you can visit the <a href="about-us">About Us</a> page.</p>
		</div>
		<button class="accordion"><p>Getting Started</p></button>
		<div class="panel">
		<p>To get started on Tonality, you should create an account username and password by <a href="signup">signing up</a>. Before you get going, make sure to read the <a href="terms">User Agreements</a>, and then get to gassing!</p>
		</div>
		<button class="accordion"><p>What is gas/trash?</p></button>
		<div class="panel">
		<p>Gassing and Trashing are the core functions of Tonality’s unique rating system. Each user can vote on any song one time and either Gas It (upvote), or Trash It (downvote). A song’s gas or trash percentage is calculated by dividing the number of gasses by the total number of votes on that song. If a song’s rating is below 60 percent, it’s labeled as trash, if it’s above 60 percent, it’s gas. We use gas and trash ratings to calculate our All-Time and Monthly Charts which you can find under the charts dropdown above.
		<br>Every vote counts! To start gassing and trashing, <a href="login">sign in</a>.</p>
		</div>
		<button class="accordion"><p>Managing Your Account</p></button>
		<div class="panel">
		<p>You can view your account by navigating to your 
		<?php if(isset($_SESSION['userID'])){ ?>
			<a href="profile?id=<?php echo $_SESSION['userID'] ?>">profile</a>
		<?php }else{ ?>
			profile
		<?php } ?>
		. Here you can view your followers, following, gassed and trashed songs, and account notifications. To change your account information or reset your password, go to the 
		<?php if(isset($_SESSION['userID'])){ ?>
			<a href="update-profile">Update Profile</a>
		<?php }else{ ?>
			Update Profile
		<?php } ?>
		 page. To see what information Tonality uses, check out the <a href="privacy">Privacy Agreement</a>. </p>
		</div>
		<button class="accordion"><p>What Is Tonality Community?</p></button>
		<div class="panel">
		<p>The Community page is Tonality’s forum where you can discuss all things music. Once you’re signed in, join the conversation by creating a topic or joining an existing discussion. Check out Tonality Community <a href="community">here</a>.</p>
		</div>
		<button class="accordion"><p>Enjoy Tonality While Streaming From Spotify</p></button>
		<div class="panel">
		<p>Tonality allows you to vote on your favorite music while you’re streaming from Spotify. To access this feature, open Spotify on your device, start streaming, then open Tonality and link your Spotify account to the website by pressing “Vote while you listen on Spotify,” and following the sign-in instructions. </p>
		</div>
		<button class="accordion"><p>How Do I Contact Tonality Directly?</p></button>
		<div class="panel">
		<p>To talk directly with the Tonality Team, fill out our <a href="contact">Contact Form</a>.</p>
		</div>
		<button class="accordion"><p>What are Tonality badges?</p></button>
		<div class="panel">
		<p>Badges are your reward for engaging with Tonality. You can earn badges for gassing or trashing songs and writing reviews. You can see the list of badges below.</p>
		<h5>Gas/Trash Badges</h5>
		<ul style="list-style-type:none;margin-top:-22px">
		<li><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:#CD7F32" height="24" width="24" fill="currentColor" class="bi bi-vinyl" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM4 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
										  <path d="M9 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
										</svg>  Bronze Rater - 50 Songs Rated</li>
		<li><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:#C0C0C0" height="24" width="24" fill="currentColor" class="bi bi-vinyl" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM4 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
										  <path d="M9 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
										</svg>  Silver Rater - 300 Songs Rated</li>
		<li><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:	#FFD700" height="24" width="24" fill="currentColor" class="bi bi-vinyl" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM4 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
										  <path d="M9 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
										</svg>  Gold Rater - 1000 Songs Rated</li>
		<li><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:	red" height="24" width="24" fill="currentColor" class="bi bi-vinyl" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM4 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0z"/>
										  <path d="M9 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
										</svg>  Supersonic Rater - 5000 Songs Rated</li>
		</ul>
		<h5>Written Review Badges</h5>
		<ul style="list-style-type:none;margin-top:-22px">
		<li><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:#CD7F32" height="18" width="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
										  <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
										</svg>  Bronze Reviewer - 5 Written Reviews</li>
		<li><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:#C0C0C0" height="18" width="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
										  <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
										</svg>  Silver Reviewer - 20 Written Reviews</li>
		<li><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:	#FFD700" height="18" width="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
										  <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
										</svg>  Gold Reviewer - 50 Written Reviews</li>
		<li><svg xmlns="http://www.w3.org/2000/svg" class="mgb-0" style="color:	red" height="18" width="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
										  <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
										</svg>  Supersonic Reviewer - 200 Written Reviews</li>
		</ul>
		<h5>Verification</h5>
		<ul style="list-style-type:none;margin-top:-22px">
		<li><svg xmlns="http://www.w3.org/2000/svg" style="color:#3f729b" height="18" width="18" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
										  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
										</svg>  Verified User</li>
		</ul>
		<p style="margin-top:-22px">Some Tonality users that have been recognized by the Tonality Team get verified. Verification is typically reserved for music artists, influencers, and recognized members of the Tonality community. If you believe you should be verified, check out the Tonality <a href="contact">Contact Form</a> to reach out to our team.</p>
		<h5>Mods and Developers</h5>
		<ul style="list-style-type:none;margin-top:-22px">
		<li><svg xmlns="http://www.w3.org/2000/svg" style="color:#1CB96A" height="18" width="18" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
										  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
										  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
										</svg>  Tonality Moderator</li>
		<li><svg xmlns="http://www.w3.org/2000/svg" style="color:#fa4895" height="18" width="18" fill="currentColor" class="bi bi-code-slash" viewBox="0 0 16 16">
										  <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294l4-13zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0zm6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0z"/>
										</svg>  Tonality Developer</li>
		</ul>
		<p style="margin-top:-22px">Moderators and Developers help keep Tonality afloat. They are special members of the Tonality Team that ensure users are following the rules and help to remove content that violates the community guidelines outlined in the <a href="terms">User Agreements</a>.</p>
		<p>You can see if you’ve earned any badges on your 
		<?php if(isset($_SESSION['userID'])){ ?>
			<a href="profile?id=<?php echo $_SESSION['userID'] ?>">profile</a>.
		<?php }else{ ?>
			profile.
		<?php } ?>
		</p>
		</div>
	</div>
</div>

<?php include "footer.php";
include "script.php"; ?>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>
</body>
</html>
