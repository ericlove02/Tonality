<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>

<body class="">
<?php include "navbar.php"; ?>

<?php if($_SESSION['user_level'] == 3){ ?>
<?php
include "db_userdata_connect.php";
$mods = $usersqli->query("SELECT first_name, last_name, user_image, user_name, userID FROM data WHERE user_level>=2");
$verified = $usersqli->query("SELECT first_name, last_name, user_image, user_name, userID FROM data WHERE user_level=1");
?>

<h1 style="width:450px;margin: 20px auto">ADMIN DASHBOARD</h1>
<div style="width:50%;margin: 0 auto;">
<a href="mod_dashboard.php">Switch to Moderator Dashboard</a>
<p>Sign into the tonality Spotify account on a guest tab, and access the Admin Dashboard through Tonality, then use the button to update the playlists</p>
<button class="btn btn--primary" style="padding:10px;" id="update-playlists">Update Playlists</button><br><br><br>
<h4>Change user level:</h4>
<form action="set_user_level.php" method="POST">
	<label for="username">User Name</label>
	<input type="text" name="username"></input>
	<label for="userId">User ID</label>
	<input type="text" name="userId"></input>
	<label for="userlevel">New User Level</label>
	<input type="text" name="userlevel"></input>
	<input type="submit" class="btn btn--primary"></input>
</form>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
			<h2>Tonality Moderators</h2>
			<table>
				<tr>
					<th>Mod Name</th>
					<th>Image</th>
					<th>Username</th>
					<th>User Id</th>
				</tr>
				<?php while($mod = $mods->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $mod['first_name'] . " " . $mod['last_name'] ?></td>
					<td><img style="height:30px;width:30px" src="<?php echo $mod['user_image'] ?>" /></td>
					<td><?php echo "<a href='profile?id=".$mod['userID'] ."' target='_blank'>" .$mod['user_name'] . "</a>" ?></td>
					<td><?php echo $mod['userID'] ?></td>
				</tr>
				<?php } ?>
			</table>
			<h2>Tonality Verified Accounts</h2>
			<table>
				<tr>
					<th>Account Name</th>
					<th>Image</th>
					<th>Username</th>
					<th>User Id</th>
				</tr>
				<?php while($acct = $verified->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $acct['first_name'] . " " . $acct['last_name'] ?></td>
					<td><img style="height:30px;width:30px" src="<?php echo $acct['user_image'] ?>" /></td>
					<td><?php echo "<a href='profile?id=".$acct['userID'] ."' target='_blank'>" .$acct['user_name'] . "</a>" ?></td>
					<td><?php echo $acct['userID'] ?></td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>

</div>
<br>
<?php }else{ ?>
<br>
<br>
<p style="padding: 20px;margin-left:25%">This page is restricted to Tonality Admin only</p>
<?php 
}

include "footer.php";
include "script.php";
?>
<script>
function resetButton(){
	document.getElementById('update-playlists').innerHTML = "Update Playlists";
	document.getElementById('update-playlists').style = "padding:10px;";
}
$("#update-playlists").click(function(){
	$.ajax({
		type: "POST",
		url: "update_tonality_playlists.php"
	}).done(function(success){
		if(success){
			document.getElementById('update-playlists').innerHTML = "Success!";
		}else{
			document.getElementById('update-playlists').innerHTML = "FAILED";
			document.getElementById('update-playlists').style = "background-color:red;padding: 10px;";
		}
		setTimeout(resetButton, 3000);
	});
});
</script>

</body>
</html>