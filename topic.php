<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <?php include 'db_userdata_connect.php'; 
            $sql = "SELECT topic_id,topic_subject,topic_cat,topic_by FROM topics WHERE topics.topic_id = " . $_GET['id'];
            $result = $usersqli->query($sql);

        ?>
        <div class="main-container">
            <section>
                <div class="container">
                    <div class="row justify-content-left">
                        <div class="col-md-12 col-lg-12">
                            <div class="boxed boxed--border">
                                <div class="row justify-content-center">                       
                                        <?php if (!$result) { ?>
                                            <p class="lead">The topic could not be displayed, please try again later.</p>
                                        <?php } else { ?>
                                            <?php if ($result->num_rows == 0) { ?>
                                                <p class="lead">This topic doesn&prime;t exist.</p>
                                            <?php } else { ?>
                                            
                                            <?php while ($row = $result->fetch_assoc()) { 
                                                $topicName = $row['topic_subject'];
                                                $posts_sql = "SELECT posts.post_topic,posts.post_id,posts.post_content,posts.post_date,posts.post_by,data.userID,data.user_name,data.user_image,data.user_level FROM
                                                posts LEFT JOIN data ON posts.post_by = data.userID WHERE posts.post_topic = " . $_GET['id'] . "  ORDER BY posts.post_date ASC";
                                                $posts_result = $usersqli->query($posts_sql);
                                                ?>
                                                <?php if (!$posts_result) { ?>
                                                    <p class="lead">The posts could not be displayed, please try again later.</p>
                                                <?php } else { 
                                                    $first = true;
                                                ?>
                                                <div class="col-md-1 col-lg-1">
                                                    <a href="category?id=<?php echo $row['topic_cat']; ?>"><img height="30" src="images/back-arrow.png" class="backarrow"></a><br>
                                                    <a href="#bottom"><img height="30" src="images/back-arrow.png" class="downarrow"></a>
                                                </div>
                                                <div class="col-md-11 col-lg-11">
                                                    <div class="comments">
                                                    <ul class="comments__list">
                                                    <li>
                                                     <?php while ($posts_row = $posts_result->fetch_assoc()) { ?>
                                                        <?php if ($first) { ?>
                                                        <div class="comment">
                                                            <div class="comment__avatar">
                                                                <img alt="Image" src="<?php echo $posts_row['user_image']; ?>">
                                                            </div>
                                                            <div class="comment__body">
                                                                <h5 class="type--fine-print"><?php echo $topicName; ?></h5>
                                                                <div class="comment__meta">
                                                                   <span><a href="profile?id=<?php echo $posts_row['userID']; ?>">@<?php echo $posts_row['user_name']; ?></a>
                                                                   </span>
                                                                   <?php
                                                                   if ($posts_row['user_level'] == 1) {
                                                                        echo '<span style="color:#3075db">&#9679;</span>';
                                                                    }
                                                                    if ($posts_row['user_level'] == 3) {
                                                                        echo '<span style="color:#fa4895">&#9679;</span>';
                                                                    }
                                                                    if ($posts_row['user_level'] >= 2) {
                                                                        echo '<span style="color:#29cc70">&#9679;</span>';
                                                                    } 
                                                                    ?>
                                                                   <br>
                                                                    <span><?php echo date('n/j/y g:ia', strtotime($posts_row['post_date'])); ?></span>
                                                                    <?php if (isset($_SESSION['userID'])) { 
                                                                        $user_level = $usersqli->query("SELECT user_level FROM data WHERE userID = " . $_SESSION['userID'])->fetch_assoc() ['user_level'];
                                                                        if ($_SESSION['userID'] == $posts_row['post_by'] || $user_level >= 2) {
																			echo "<a href='delete_post.php?topic=".$row['topic_id']."&post=".$posts_row['post_id']."' style='float:right' target='target' onclick='reload()'><svg xmlns=\"http://www.w3.org/2000/svg\" style=\"color:red;width:36px;height:36px;margin-top:-10px;\" fill=\"currentColor\" class=\"bi bi-x\" viewBox=\"0 0 16 16\">
																				  <path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"/>
																				</svg></a>";
                                                                     } } ?>
																	 <svg class="flag-button" id="flag-<?php echo $posts_row['post_id'] ?>" data-id="<?php echo $posts_row['post_id'] ?>" xmlns="http://www.w3.org/2000/svg" style="color:#9c9c9c;float:right;margin-right:-16px" width="20" height="20" fill="currentColor" class="bi bi-flag" viewBox="0 0 16 16">
																		<path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z"/>
																	</svg>
                                                                </div>
                                                                <p>
                                                                    <?php echo stripslashes($posts_row['post_content']); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <?php $first = false; } else { ?>
                                                        <!--end comment-->
                                                        <div class="comment">
                                                            <div class="comment__avatar">
                                                                <img alt="Image" src="<?php echo $posts_row['user_image']; ?>">
                                                            </div>
                                                            <div class="comment__body">
                                                                <h5 class="type--fine-print"><?php echo $topicName; ?></h5>
                                                                <div class="comment__meta">
                                                                   <span><a href="profile?id=<?php echo $posts_row['userID']; ?>">@<?php echo $posts_row['user_name']; ?></a>
                                                                   </span>
                                                                   <?php
                                                                   if ($posts_row['user_level'] == 1) {
                                                                        echo '<span style="color:#3075db">&#9679;</span>';
                                                                    }
                                                                    if ($posts_row['user_level'] == 3) {
                                                                        echo '<span style="color:#fa4895">&#9679;</span>';
                                                                    }
                                                                    if ($posts_row['user_level'] >= 2) {
                                                                        echo '<span style="color:#29cc70">&#9679;</span>';
                                                                    } 
                                                                    ?>
                                                                   <br>
                                                                    <span><?php echo date('n/j/y g:ia', strtotime($posts_row['post_date'])); ?></span>
                                                                    <?php if (isset($_SESSION['userID'])) { 
                                                                        $user_level = $usersqli->query("SELECT user_level FROM data WHERE userID = " . $_SESSION['userID'])->fetch_assoc() ['user_level'];
                                                                        if ($_SESSION['userID'] == $posts_row['post_by'] || $user_level >= 2) {
																			echo "<a href='delete_post.php?topic=".$row['topic_id']."&post=".$posts_row['post_id']."' style='float:right' target='target' onclick='reload()'><svg xmlns=\"http://www.w3.org/2000/svg\" style=\"color:red;width:36px;height:36px;margin-top:-10px;\" fill=\"currentColor\" class=\"bi bi-x\" viewBox=\"0 0 16 16\">
																				  <path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"/>
																				</svg></a>";
                                                                     } } ?>
																	 <svg class="flag-button" id="flag-<?php echo $posts_row['post_id'] ?>" data-id="<?php echo $posts_row['post_id'] ?>" xmlns="http://www.w3.org/2000/svg" style="color:#9c9c9c;float:right;margin-right:-16px" width="20" height="20" fill="currentColor" class="bi bi-flag" viewBox="0 0 16 16">
																		<path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z"/>
																	</svg>
                                                                </div>
                                                                <p>
                                                                    <?php echo stripslashes($posts_row['post_content']); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                                    </li>
                                                </ul>
                                            <?php } ?>
                                        <?php } ?>
                                        </div>
                                        <!--end comments-->
                                        <?php if (!isset($_SESSION['userID'])) { ?>
                                        <p class="lead">You must be <a href="login" style="color:#a3d2ca">signed in</a> to reply. You can also <a href="signup" style="color:#a3d2ca">sign up</a> for an account.</p>
                                        <?php } else { ?>
                                            <div class="comments-form">
                                                <h4 id="bottom">Reply</h4>
                                                <form class="row" method="post" action="reply.php?id=<?php echo $_GET['id']; ?>">
                                                    <div class="col-md-12">
                                                        <textarea rows="4" name="reply-content" id="topic-reply"></textarea>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button class="btn btn--primary" type="submit">Submit reply</button>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
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
		<script src="https://cdn.tiny.cloud/1/40hct26l99thitiqzooduhp5jwcna4y5o9mgb5axydeeiom6/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
		<script>
		tinymce.init({
		  selector: '#topic-reply',
		  height: 300,
		  skin: 'outside',
		  icons: 'thin',
		  toolbar_location: 'bottom',
		  menubar: false,
		  plugins: [
			'advlist autolink lists link image charmap print preview anchor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table paste imagetools wordcount'
		  ],
		  toolbar: 'styleselect | bold italic link underline strikethrough | image media', 
		  placeholder: 'Leave a Reply...'
		});
		$('.flag-button').on('click', function(){
			var buttonId = $(this).attr('id');
			var button = document.getElementById(buttonId);
			$.ajax({
				type: "POST",
				url: "flag_post.php",
				data: { 'id':button.getAttribute('data-id') }
			}).done(function(){
				console.log('post flagged');
				button.style = "color:orange;float:right;margin-right:-16px";
			});
		});
		</script>
    </body>
</html>