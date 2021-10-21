<?php
include "db_userdata_connect.php";
if (!isset($_SESSION)) {
	session_start();
}
if((!isset($_SESSION['signed_in']) || !$_SESSION['signed_in']) && isset($_COOKIE['un']) && isset($_COOKIE['pw'])){
	$user_name = $_COOKIE['un'];
	$input_password = $_COOKIE['pw'];
	include 'signinuser.php';
}else if (isset($_SESSION['signed_in']) && isset($_SESSION['userID'])){
	header('Location: /home');
}

include 'Mobile_Detect.php';
$detect = new Mobile_Detect();

$isMobile = $detect->isMobile();

header('Location: home');
?>
<!doctype html>
<html lang="en">
<head>
    <?php include "head.php"; ?>
	<style>
	.outside-container{
	  position:relative;
	  width:100vw;
	  height: 100vh;
	  margin: auto;
	}

	.container{
	  position:absolute;
	  top: 0; bottom: 0; left: 0; right: 0;
	  margin: auto;
	  height: 60vh;
	}

	/* just aesthetics */
	body{
	  height: 100vh;
	  display:flex;
	  background:#fcfcfc;
	}
	</style>
</head>
<body class="">

    <div class="main-container">
        <?php
            if (isset($_GET["message"])) {
                if ($_GET["message"] == "user") {
                    echo "<div class='p-3 mb-2 bg-danger text-white text-center'>That username does not exist. Try pressing Sign Up</div>";
                }
                else if ($_GET["message"] == "pw") {
                    echo "<div class='p-3 mb-2 bg-danger text-white text-center'>That username/password combination doesn't match our records</div>";
                }
                else if ($_GET["message"] == "reset") {
                    echo "<div class='p-3 mb-2 bg-success text-white text-center'>An email has been sent to reset your password</div>";
                }
				else if ($_GET["message"] == "pw-success") {
                    echo "<div class='p-3 mb-2 bg-success text-white text-center'>Your password has been updated</div>";
                }
				else if ($_GET["message"] == "new") {
                    echo "<div class='p-3 mb-2 bg-success text-white text-center'>Welcome to Tonality! Sign in to get started</div>";
                }
            }
        ?>
        <section class="outside-container">
            
            <div class="container">
                <div class="row justify-content-center no-gutters" style="margin-bottom:52px;">
					<?php if($isMobile){ ?>
						<div class="col-lg-4 " style="padding:50px" id="mobile-none">
							<img src="images/tonality-welcome.gif" style="width:300px;margin-top:50px" />
						</div>
					<?php } ?>
                    <div class="col-md-10 col-lg-4">
                        <div class="boxed boxed--border" style="<?php if($isMobile){ echo "margin-top:-80px"; } ?>">
						<div class="text-center">
							<img src="images/tonality-logo.png" style="width:150px" />
						</div>
                            <form  class="row mx-0 " action="signinuser.php" method="post" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <input type="text" name="user" required="required" placeholder="Username">
                                    <input type="password" id="pass" name="pass" required="required" placeholder="Password">
                                    <div class="input-checkbox">
                                        <input type="checkbox" name="showPass" id="showPass" >
                                        <label for="showPass" onclick="showPassword()"></label>
                                    </div>
                                    <span>Show Password</span>
									<br>
									<div class="input-checkbox">
										<input type="checkbox" name="rem" id="rem">
										<label for="rem"></label>
									</div>
									<span>Remember Me</span>
								
                                    <button type="submit" class="btn btn--primary type--uppercase">Sign In</button>
									<span class="type--fine-print block text-center" style="padding-bottom:8px">                           
										<a href="reset">Forgot Password?</a>
									</span>
									<span class="type--fine-print block text-center" style="border-top:1px solid black;padding-top:8px"> 
										Don't have an account yet?
										<a href="signup"> Sign Up</a>
									</span>
                                </div>
                            </form>
                            
                        </div>
                    
						<div class="boxed boxed--border">
							<div class="text-center">
                                <span class="type--fine-print block "> 
                                    <h4 style="margin-bottom:-8px"><a href="explore">Explore</a>
									 without signing in</h4>
                                </span>
                                
                            </div>
						</div>
						<div class="">
							<div class="text-center">
                                <span class=""> 
                                    Get the Tonality App
                                </span><br>
                                <a data-tooltip="Coming Soon!"><img src="images/apple-coming-soon.png" style="display:inline-block;width:120px"/></a>
								<a data-tooltip="Coming Soon!"><img src="images/google-coming-soon.png" style="display:inline-block;width:120px"/></a>
                            </div>
						</div>
					</div>
					
                </div>
                <!--end of row-->
				<div style="margin-bottom:12px;max-width:70%;width:fit-content;justify-content:flex-start;margin:0 auto">
					<div style="margin-right:8px;margin-left:8px;margin-bottom:12px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
						<a href="about-us">About</a>
					</div>
					<div style="margin-right:8px;margin-left:8px;margin-bottom:12px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
						<a href="help">Help</a>
					</div>
					<div style="margin-right:8px;margin-left:8px;margin-bottom:12px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
						<a href="privacy">Privacy</a>
					</div>
					<div style="margin-right:8px;margin-left:8px;margin-bottom:12px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
						<a href="terms">Terms</a>
					</div>
				</div>
				<div style="margin-bottom:52px;max-width:70%;width:fit-content;justify-content:flex-start;margin:0 auto">
					<div style="margin-right:8px;margin-left:8px;margin-bottom:12px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
						<span class="type--fine-print">&copy;
						<span class="update-year"></span> Tonality</span>
					</div>
					<div style="margin-right:8px;margin-left:8px;margin-bottom:12px;flex:0 0 auto;align-content:stretch;position:relative;vertical-align:baseline;display:inline-block;">
						<span class="type--fine-print">Powered by <a href="https://www.spotify.com" target="_blank" style="text-decoration:none">Spotify</a>&reg;</span>
					</div>
				</div>
            </div>
            <!--end of container-->
        </section>
    </div>
	
<?php
//include "footer.php";
include "script.php";
?>
<script type="text/javascript">
    showPassword = function(){
        var x = document.getElementById("pass");
        if (x.type === "password") {
            x.type = "text";
        } 
        else {
           x.type = "password";
        }
    }
</script>
</body>
</html>