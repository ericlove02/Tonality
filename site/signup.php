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

	.tooltip2 {
	  position: relative;
	  display: inline-block;
	  top:8px;
	}

	.tooltip2 .tooltiptext {
	  visibility: hidden;
	  width: 300px;
	  background-color: black;
	  color: #fff;
	  text-align: center;
	  border-radius: 6px;
	  padding: 5px 0;
	  position: absolute;
	  z-index: 1;
	  bottom: 150%;
	  left: 50%;
	  margin-left: -280px;
	}

	.tooltip2 .tooltiptext::after {
	  content: "";
	  position: absolute;
	  top: 100%;
	  left: 50%;
	  margin-left: 125px;
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
        <section class="outside-container">
            
            <div class="container">
                <div class="row justify-content-center no-gutters" style="margin-bottom:52px;">
					
                <div class="text-center">
            		<h3>Welcome to Tonality!</h3>
            	</div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="boxed boxed--lg boxed--border">
						<div class="text-center">
							<img src="images/tonality-logo.png" style="width:150px" />
						</div>
                            <form  class="row mx-0 " action="create_user.php" method="post" enctype="multipart/form-data">
                                <div class="col-md-6">
                                    <input type="text" id="first" name="first" required="required" oninput="updatePreview()" placeholder="First Name">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="last" name="last" required="required" oninput="updatePreview()" placeholder="Last Name">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="user" name="user" required="required" oninput="userRequirements(this); updatePreview()" placeholder="Username">
                                    <p>Username must be <span id="ulen" style="">between 4-20 characters</span> and <span id="uchars">cannot contain special characters</span></p>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="email" required="required" oninput="validEmail(this)" placeholder="Email">
                                    <p id="emailp" style="display:none">Enter a valid email</p>
                                </div>
                                <div class="col-md-12">
                                    <input type="password" name="pass" id="pass" required="required" oninput="passwordRequirements(this)" placeholder="Password">
                                    <div class="input-checkbox">
                                        <input type="checkbox" name="showPass" id="showPass" >
                                        <label for="showPass" onclick="showPassword()"></label>
                                    </div>
                                    <span>Show Password</span>
                             		<p>Password must be <span id="len" style="">between 6-20 characters</span> and <span id="chars">include an uppercase letter, lowercase letter, and special character</span></p>
                                </div>
                                <div class="col-md-12">
                                    <input type="password" name="conpass" id="conpass" required="required" oninput="passwordRequirements(document.getElementById('pass'))" placeholder="Confirm Password">
                                    <p id="confirmp" style="display:none">Passwords must match</p>
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
                                <div class="col-md-12">
                                	<div class="g-recaptcha" data-sitekey="6Led0ncaAAAAANY7GzneClTWt2aCVoOUvXOsAraO"></div>
                                </div>
                                <div class="col-md-12 boxed">
									<div class="input-checkbox">
										<input type="checkbox" name="rem" id="rem">
										<label for="rem"></label>
									</div>
									<span>Remember Me</span>
                                    <button type="submit" id="submit" class="btn btn--primary type--uppercase">Sign Up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="boxed boxed--lg boxed--border">
                            <div class="text-block text-center">
                                <img alt="avatar" id="preview" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="image--md" style="max-height: 15.428571em;" />
                                <h3 id="name">Your Name</h3>
                                <span class="h5" id="username"></span>
                                <span class="type--fine-print block"> 
                                    Already have an account?
                                    <a href="login"> Log In</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end of row-->
				<div class="col-lg-12">
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
	changeAction = function(link){
	   if(link.value == ""){
		  document.getElementById("preview").src = "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png";
	   }else{
		   document.getElementById("preview").src = link.value;
	   }
	}

	let specialChars = ["+", "-", "&", "|", "!", "(", ")", "#", "{", "}", "[", "]", "^", "~", "*", "?", ":", "@", "$", "%", "_", "=", "/", "\\", "?", "\'", "."];
	let upperChars = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
	let lowerChars = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "y", "v", "w", "x", "y", "z"];
	var passwordMet = false;
	var userMet = false;

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
			document.getElementById("submit").style = "background-color:#a3d2ca;";
			document.getElementById("submit").disabled = false;
		}else{
			document.getElementById("submit").style = "background-color:#d1e8e4;";
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