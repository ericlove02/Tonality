<?php 
	include 'db_userdata_connect.php';
	if(!isset($_SESSION)){
		session_start();
	}
	
	if ($_POST['search'] != "") { 
	$user_to_search = $_POST['search'];
	$sql = "SELECT userID, user_name, first_name, last_name, user_image, user_level FROM data WHERE user_name LIKE '%" . $user_to_search . "%' OR first_name LIKE '%" . $user_to_search . "%' OR last_name LIKE '%" . $user_to_search . "%' LIMIT 100";
?>
<?php } else { 
	//$sql = "SELECT userID, user_name, first_name, last_name, user_image, user_level FROM data WHERE 1";
	$sql = "Select data.userID, data.user_name, data.first_name, data.last_name, data.user_image, data.user_level, count(user_votes.vote_by) as num_votes
			From data 
			left join user_votes 
			on data.userID=user_votes.vote_by
			group by data.userID, data.user_name
			order by num_votes DESC
			";
}
?>

<table>
	<tbody>
		<?php  
			$result = $usersqli->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$name = $row["first_name"] . " " . $row["last_name"];
					$rated_songs = $usersqli->query("SELECT song_id FROM user_votes WHERE vote_by=" . $row["userID"])->num_rows;
		?>
	   
		<tr>
			<td>
				<a href="profile?id=<?php echo $row["userID"]; ?>"><img src="<?php echo $row["user_image"]; ?>" class="img-responsive" height="50"  /></a>
			</td>
			<td class="font-20"><a href="profile?id=<?php echo $row["userID"]; ?>"><?php echo $row["user_name"]; ?>
				<?php if ($row['user_level'] == 1) {
					echo '<span style="color:#3075db">&#9679;</span>';
				}
				if ($row['user_level'] == 3) {
					echo '<span style="color:#fa4895">&#9679;</span>';
				}
				if ($row['user_level'] >= 2) {
					echo '<span style="color:#29cc70">&#9679;</span>';
				} ?></a>
			</td>
			<td class="font-20">
				<a href="profile?id=<?php echo $row["userID"]; ?>"><?php echo $name; ?></a>
			</td>
			<td>
				<strong># songs rated: <?php echo $rated_songs; ?></strong>
			</td>
			<td>
				<?php if (isset($_SESSION["userID"]) && !($_SESSION["userID"] == $row["userID"])) { 
					if ($usersqli->query("SELECT follower_id FROM follows WHERE follower_id=".$_SESSION['userID']." AND following_id=".$row["userID"])->num_rows > 0) {
					?>
						<button id="follow-button-<?php echo $row["userID"]; ?>" style="color: #fff;padding:2px 10px" class="btn btn--primary" data-follow="<?php echo $row["userID"]; ?>" data-user="<?php echo $_SESSION["userID"] ?>">Unfollow</button>
					<?php } else { ?>
						<button id="follow-button-<?php echo $row["userID"]; ?>" style="color: #fff;padding:2px 10px" class="btn btn--primary" data-follow="<?php echo $row["userID"]; ?>" data-user="<?php echo $_SESSION["userID"] ?>">Follow</button>
					<?php } ?>
				<?php } else if (isset($_SESSION["userID"]) && ($_SESSION["userID"] == $row["userID"])) { ?>
				<?php } else { ?>
					<button type="submit" class="btn btn--primary" disabled="true" >Sign in to Follow</button>
				<?php } ?>
			</td>
		</tr>
		
		<?php } 
			} else { ?>
			<tr>0 results</tr>
		<?php } 
		$usersqli->close();
		?>
	   
	</tbody>
</table>   
<script>
<?php
include "script.php";
?>
</script>