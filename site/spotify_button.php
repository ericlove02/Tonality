<!DOCTYPE html>
<html>
<head>
	<?php include "head.php"; ?>
</head>
<body>
<script>
function setIframe(){
	location.href = "/current_song.php";
	window.open("/spotify_signin.php", "_blank");
}
</script>
<button class='btn btn--primary' onclick="setIframe()" style="margin:10px;padding:4px">Sign In with Spotify</button>
</body>
</html>