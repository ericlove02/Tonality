<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>

<body class="">
<?php include "navbar.php"; ?>

<p style="padding: 20px;margin-left:25%">You have successfully signed into <a href="https://www.spotify.com/" target="_blank" style="color:green">Spotify</a>, you can close this page</p>

<?php
$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($_SESSION['token']);
try{
	if($api->play()){
		$isPremium = True;
	}
}catch(Exception $e){
	//echo $e->getMessage();
	if(strpos($e->getMessage(), "Premium")){
		$isPremium = False;
	}else{
		$isPremium = True;
	}
}
$_SESSION['isPremium'] = $isPremium;

if($isPremium){ ?>
	<p style="padding: 20px;margin-left:25%">We detected that you are using a Spotify Premium account. Enjoy Tonality's full features!</p>
<?php }else{ ?>
	<p style="padding: 20px;margin-left:25%">We detected that you are using a Spotify Free account. If you upgrade to Spotify Premium, you can get even more out of Tonality!</p>
<?php } ?>

<?php 
include "footer.php";
include "script.php";
?>

</body>
</html>