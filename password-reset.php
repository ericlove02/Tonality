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
            if (!(isset($_SESSION))) {
                session_start();
            }
            if (isset($_GET['hu'])) {
                $_SESSION['hu'] = $_GET['hu'];
            }
            else {
                echo "<div class='p-3 mb-2 bg-danger text-white text-center'>There is no user attached to this reset. Please follow the link you received</div>";
            }
            if (isset($_GET["message"])) {
                if ($_GET["message"] == "user") {
                    echo "<div class='p-3 mb-2 bg-danger text-white text-center'>That is not the correct username</div>";
                }
            }
        ?>
        <section class="outside-container">
            
            <div class="container">
                <div class="row justify-content-center no-gutters" style="margin-bottom:52px;">
					
                    <div class="col-md-10 col-lg-8">
                        <div class="boxed boxed--border">
						<div class="text-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" fill="currentColor" style="color:black" class="bi bi-key" viewBox="0 0 16 16"> 
								<path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/> 
								<path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/> 
							</svg>
						</div>
						<div class="text-center">
						<h3 style="margin-bottom:0">Tonality Password Reset</h3>
						</div>
							<form  class="row mx-0 " action="do_pw_reset.php" method="post" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <span>Username</span>
                                    <input type="text" id="user" name="user" oninput="userRequirements(this)" required>
                                </div>
                                <div class="col-md-12">
                                    <span>Password</span>
                                    <input type="password" name="pass" id="pass"  oninput="passwordRequirements(this)" required>
                                    <div class="input-checkbox">
                                        <input type="checkbox" name="showPass" id="showPass" >
                                        <label for="showPass" onclick="showPassword()"></label>
                                    </div>
                                    <span>Show Password</span>
                                    <p>Password must be <span id="len" style="">between 6-20 characters</span> and <span id="chars">include an uppercase letter, lowercase letter, and special character</span></p>
                                </div>
                                <div class="col-md-12">
                                    <span>Confirm Password</span>
                                    <input type="password" name="conpass" id="conpass" oninput="passwordRequirements(document.getElementById('pass'))" required>
                                    <p id="confirmp" style="display:none">Passwords must match</p>
                                </div>
                                <div class="col-md-12 boxed">
                                    <button type="submit" id="submit" disabled="true" class="btn btn--primary type--uppercase">Reset Password</button>
                                </div>
                            </form>
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
    let specialChars = ["+", "-", "&", "|", "!", "(", ")", "#", "{", "}", "[", "]", "^", "~", "*", "?", ":", "@", "$", "%", "_", "=", "/", "\\", "?", "\'"];
    let upperChars = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    let lowerChars = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "y", "v", "w", "x", "y", "z"];
    var passwordMet = false;
    var userMet = false;
    passwordRequirements = function(select){
        var pw = select.value;
        if (!(specialChars.some(v => pw.includes(v)) && lowerChars.some(v => pw.includes(v)) && upperChars.some(v => pw.includes(v)))){
            document.getElementById("chars").style = "color:red";
        }else{
            document.getElementById("chars").style = "color:green";
        }
        if (pw.length < 6 || pw.length > 20){
            document.getElementById("len").style = "color:red";
        }else{
            document.getElementById("len").style = "color:green";
        }
        if(pw == document.getElementById("conpass").value){
            document.getElementById("confirmp").style = "display:none";
        }else{
            document.getElementById("confirmp").style = "color:red";
        }
        if(specialChars.some(v => pw.includes(v)) && lowerChars.some(v => pw.includes(v)) 
            && upperChars.some(v => pw.includes(v)) && !(pw.length < 6 || pw.length >= 20)
            && pw == document.getElementById("conpass").value){
            passwordMet = true;
            }
            else{
                passwordMet = false;
            }
        if(passwordMet && userMet){
            document.getElementById("submit").style = "background-color:#a3d2ca;";
            document.getElementById("submit").disabled = false;
        }else{
            document.getElementById("submit").style = "background-color:#d1e8e4;";
            document.getElementById("submit").disabled = true;
        }
    }
    userRequirements = function(select){
        var user = select.value;
        if(!(specialChars.some(v => user.includes(v))) && !(user.length < 4 || user.length > 20)){
            userMet = true;
        }
        else{
            userMet = false;
        }
        
        
        if(passwordMet && userMet){
            document.getElementById("submit").style = "background-color:#a3d2ca;";
            document.getElementById("submit").disabled = false;
        }else{
            document.getElementById("submit").style = "background-color:#d1e8e4;";
            document.getElementById("submit").disabled = true;
        }
    }
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
