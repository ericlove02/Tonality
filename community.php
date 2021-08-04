<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <?php
            include 'db_userdata_connect.php';
            $sql = "SELECT categories.cat_id,categories.cat_name,categories.cat_description,COUNT(topics.topic_id) AS topics FROM categories LEFT JOIN topics ON
                        topics.topic_id = categories.cat_id GROUP BY categories.cat_name, categories.cat_description, categories.cat_id";
            $result = $usersqli->query($sql);
        ?>
        <section class=" ">
            <div class="container">
                <div class="top-text text-left">
                    <h3>Community Forums</h3>
                    <span>Some quick reminders from the community guidelines:</span>
                    <ul style="list-style-type:circle;margin-left:20px;margin-bottom:-25px">
						<li>Don't be weird, gross, or mean</li>
						<li>please, our mods will be sad</li>
						<li>
							<a href="terms" style="color:#59baa8;text-decoration:none">See more...</a>
						</li>
					</ul>
                </div>
				<!--<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Horizontal Res Unit -- >
				<ins class="adsbygoogle"
					 style="display:block"
					 data-ad-client="ca-pub-3114841394059389"
					 data-ad-slot="9248316743"
					 data-ad-format="auto"
					 data-full-width-responsive="true"></ins>
				<script>
					 (adsbygoogle = window.adsbygoogle || []).push({});
				</script><br><br><br>--><br>
				
				<div class="boxed boxed--lg boxed--border">
					<div class="col-lg-12">
						<h3>Search Forums</h3>
						<form>
							<div class="col-lg-12">
								<input type="text" id="search-forums" placeholder="Search...">
							</div>
						</form>
						<div id="results">
						</div>
					</div>
				</div>
				
                <div class="row">
                	<?php if (!$result) { ?>
                		<span>The categories could not be displayed, please try again later.</span>
                	<?php } else { ?>
                		<?php if ($result->num_rows == 0) { ?>
                			<span>No categories defined yet.</span>
                		<?php } else { ?>
                			<div class="flex-20 text-center"><h4>Start New Topic</h4></div>
                			<div class="flex-40"><h4>Forums Categories</h4></div>
                			<div class="flex-20"><h4>Recent Topics</h4></div>
                		<?php } ?>
                	<?php } ?>
                </div>
                <?php while ($row = $result->fetch_assoc()) { ?>
                	<div class="row community-rows">
                		<div class="flex-20 text-center mg-18">
                			<a href="create_topic.php?setId=<?php echo $row['cat_id'] ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" class="topic-plus" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
							  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
							  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
							</svg>
							</a>
                		</div>
            			<div class="flex-40">
            				<span class="h3 ch3">
            					<a href="category?id=<?php echo $row['cat_id'] ?>"><?php echo $row['cat_name'] ?></a>
            				</span>
            				<span class="font-15">
            					<?php echo $row['cat_description'] ?>
            				</span>
            			</div>
            			<?php 
	            			$topicsql = "SELECT topic_id,topic_subject,topic_date,topic_cat FROM topics WHERE topic_cat = " . $row['cat_id'] . " ORDER BY topic_date
	            			DESC LIMIT 1";
	            			$topicsresult = $usersqli->query($topicsql);
            			?>
            			<div class="flex-20 mg-18">
            				<?php if (!$topicsresult) { ?>
            					<span class="font-15">Last topic could not be displayed.</span>
            				<?php } else { ?>
            					<?php if ($topicsresult->num_rows == 0) { ?>
            						<span class="font-15 ">No topics yet, get it started!</span>
            					<?php }  else { while ($topicrow = $topicsresult->fetch_assoc()) { ?> 
            						<span class="font-15 ch4">
		            					<a href="topic?id=<?php echo $topicrow['topic_id'] ?>"><?php echo $topicrow['topic_subject'].' ' ?></a><?php echo date('m/d', strtotime($topicrow['topic_date'])); ?>
		            				</span>
            					<?php } } ?>
            				<?php } ?>
            			</div>
        			</div>	                	
                <?php } ?>
            </div>
        </section>
        <?php 
            include "footer.php";
            include "script.php";
        ?>
		<script>
		$('#search-forums').on('input', function(){
			$.ajax({
				type: "POST",
				url: "search_forums.php",
				data: { 'search':$('#search-forums').val() }
			}).done(function(content){
				document.getElementById('results').innerHTML = content;
				console.log("CONTENT LOADED");
			});
		});
		
		
		</script>
    </body>
</html>