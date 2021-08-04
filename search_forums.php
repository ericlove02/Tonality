<?php


$search = $_POST['search'];

if($search != ""){
	
	include "db_userdata_connect.php";
	
	$sql = "SELECT cat_name AS content, cat_id AS topic, 'Category' AS type 
			FROM categories 
			WHERE cat_name 
			LIKE '%".$search."%' 
			UNION
			SELECT topic_subject AS content, topic_id AS topic, 'Topic' AS type 
			FROM topics 
			WHERE topic_subject 
			LIKE '%".$search."%' 
			UNION
			SELECT post_content AS content, post_topic AS topic, 'Reply' AS type 
			FROM posts 
			WHERE post_content 
			LIKE '%".$search."%'
			LIMIT 20";
	$search_results = $usersqli->query($sql);

	if($search_results->num_rows > 0){ ?>
	<div style="max-height:50vh;overflow-y:auto">
	<table>
		<tbody>
	<?php
		while($row = $search_results->fetch_assoc()){ ?>
			<tr>
				<td><a href="<?php if($row['type']=="Category"){ ?> category?id=<?php echo $row['topic']; }else{ ?>topic?id=<?php echo $row['topic']; } ?>">
				<?php if($row['type'] == "Topic"){ ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-question-square" viewBox="0 0 16 16">
				  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
				  <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
				</svg>
				<?php }else if($row['type'] == "Reply"){ ?><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-sticky" viewBox="0 0 16 16">
				  <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1h-11zM2 2.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V8H9.5A1.5 1.5 0 0 0 8 9.5V14H2.5a.5.5 0 0 1-.5-.5v-11zm7 11.293V9.5a.5.5 0 0 1 .5-.5h4.293L9 13.793z"/>
				</svg><?php }else{ ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-view-stacked" viewBox="0 0 16 16">
					<path d="M3 0h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3zm0 8h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1H3z"/>
				</svg><?php } ?>
				 </a></td>
				<!--<td><?php //echo $row['type']; ?></td>-->
				<td><?php if(strlen(strip_tags($row['content']))<177){echo mb_substr(strip_tags($row['content']),0,180,'utf-8');}else{ echo mb_substr(strip_tags($row['content']),0,177,'utf-8') . "..."; } ?></td>
			</tr>
	<?php } ?>
		</tbody>
	</table>
	</div>
	<?php
	}else{ ?>
		0 Results
	<?php }
	$usersqli->close();
} ?>
