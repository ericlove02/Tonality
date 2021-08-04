<!doctype html>
<html lang="en">
<head>
    <?php include "head.php"; ?>
	<style>
	.outside-container{
	  position:relative;
	  width:100vw;
	  height: 90vh;
	  margin: auto;
	}

	.container{
	  position:absolute;
	  top: 0; bottom: 0; left: 0; right: 0;
	  margin: auto;
	  height: 65vh;
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
					
                    <div class="col-md-10 col-lg-6">
                        <div class="boxed boxed--border">
						<div class="text-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" fill="currentColor" style="color:black" class="bi bi-key" viewBox="0 0 16 16"> 
								<path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/> 
								<path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/> 
							</svg>
						</div>
						<div class="text-center">
						<h3 style="margin-bottom:0">Forgot Password?</h3>
						<span>Enter your account username and we will send a reset link to your email on file</span>
						</div>
						<form style="margin-top:12px;" class="row mx-0 " action="password-reset-email.php" method="post" enctype="multipart/form-data">
							<div class="col-md-12">
								<input type="text" name="user" required="required" placeholder="Username">
							</div>
							<div class="col-md-12 boxed">
								<button type="submit" class="btn btn--primary type--uppercase">Send Password Reset</button>
							</div>
						</form>
							
						<div class="text-center">
                                <span class="type--fine-print block "> 
                                    <h6 style="margin-bottom:0">- OR -</h6>
									<a href="signup">Create New Account</a>
                                </span>
                                
                            </div>
                        </div>
                    
						<div class="boxed boxed--border">
							<div class="text-center">
                                <span class="type--fine-print block font-20"> 
                                    <a href="login">Back to Login</a>
                                </span>
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