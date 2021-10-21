<?php
    require_once "rating_system.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <div class="main-container">
            <section>
                <div class="container">
                    <div class="top-text text-left">
                        <?php 
                            include 'db_userdata_connect.php';
                            if (isset($_GET["keyword"])) { 
                            $user_to_search = $_GET["keyword"];
                            $sql = "SELECT userID, user_name, first_name, last_name, user_image, user_level FROM data WHERE user_name LIKE '%" . $user_to_search . "%' OR first_name LIKE '%" . $user_to_search . "%' OR last_name LIKE '%" . $user_to_search . "%' LIMIT 100";
                        ?>
                            <h3>users related to <?php echo $user_to_search; ?></h3>
                        <?php } else { 
                            //$sql = "SELECT userID, user_name, first_name, last_name, user_image, user_level FROM data WHERE 1";
							$sql = "Select data.userID, data.user_name, data.first_name, data.last_name, data.user_image, data.user_level, count(user_votes.vote_by) as num_votes
									From data 
									left join user_votes 
									on data.userID=user_votes.vote_by
									group by data.userID, data.user_name
									order by num_votes DESC
									";
                        ?>
                            <h3>Showing all users</h3>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="boxed boxed--border">
                                <form class="text-left row mx-0" name="search" id="search" action="">
                                    <div class="col-md-8">
                                        <span>Search:</span>
                                        <input type="text" name="keyword" id="search-users" value="<?php if(isset($_GET["keyword"])){ echo $_GET["keyword"]; } ?>" onchange="searchChanged(this)" />
                                    </div>
                                    <div class="col-md-4">
                                        <span>Category:</span>
                                        <select name="search-options" id="dropdown" onchange="changeAction(this)">
                                            <option value="search?keyword=">Search All</option>
                                            <option value="search-users?keyword=" selected>Users</option>
                                            <option value="search-albums?keyword=">Albums</option>
                                            <option value="search-songs?keyword=">Song Names</option>
                                            <option value="search-artists?keyword=">Artists</option>
                                          </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="search-results" style="min-height: calc(100vh - 560px)">
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
                                        <td class="clickable-row" data-href="profile?id=<?php echo $row["userID"]; ?>">
                                            <img src="<?php echo $row["user_image"]; ?>" class="img-responsive" height="50"  />
                                        </td>
                                        <td class="clickable-row font-20" data-href="profile?id=<?php echo $row["userID"]; ?>" >    <?php echo $row["user_name"]; ?>
                                            <?php if ($row['user_level'] == 1) {
                                                echo '<span style="color:#3075db">&#9679;</span>';
                                            }
                                            if ($row['user_level'] == 3) {
                                                echo '<span style="color:#fa4895">&#9679;</span>';
                                            }
                                            if ($row['user_level'] >= 2) {
                                                echo '<span style="color:#29cc70">&#9679;</span>';
                                            } ?>
                                        </td>
                                        <td class="clickable-row font-20" data-href="profile?id=<?php echo $row["userID"]; ?>">
                                            <?php echo $name; ?>
                                        </td>
                                        <td class="clickable-row" data-href="profile?id=<?php echo $row["userID"]; ?>">
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
                        </div>
                    </div>
                    
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
        </div>
        <?php
            include "footer.php";
            include "script.php";
        ?>
		<script>
		$('.btn').on('click', function(){
			var buttonId = $(this).attr('id');
			var button = document.getElementById(buttonId);
			if(button.innerHTML == 'Follow'){
				$.ajax({
					type: "POST",
					url: "follow_user.php",
					data: { 'followed':button.getAttribute('data-follow'), 'follower':button.getAttribute('data-user') }
				}).done(function(){
					console.log('user followed');
					button.innerHTML = 'Unfollow';
				});
			}
			if(button.innerHTML == 'Unfollow'){
				$.ajax({
					type: "POST",
					url: "unfollow_user.php",
					data: { 'unfollowing':button.getAttribute('data-follow'), 'user':button.getAttribute('data-user') }
				}).done(function(){
					console.log('user unfollowed');
					button.innerHTML = 'Follow';
				});
			}
		});
		$('#search-users').on('input', function(){
			$.ajax({
				type: "POST",
				url: "update_user_search.php",
				data: { 'search':$('#search-users').val() }
			}).done(function(content){
				document.getElementById('search-results').innerHTML = content;
				console.log("CONTENT LOADED");
			});
		});
		</script>
    </body>
</html>