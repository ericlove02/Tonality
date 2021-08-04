<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
		<style>
		.article{
			transition: transform .2s ease-in;
		}
		.article:hover{
			transform: scale(1.02);
		}
		</style>
    </head>
    <body style="background-color:#fcfcfc" class="">
		<?php include "navbar.php";  ?>
		<div class="main-container">
			<section class="padding-bottom-0">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
						<h1>Tonality News</h1>
						<?php 
						include "db_userdata_connect.php";
						$articles = $usersqli->query("SELECT * FROM articles ORDER BY date DESC, article_id DESC");
						if($articles->num_rows > 0){
							while($row = $articles->fetch_assoc()){ 
								if($row['type'] == 'article'){?>
									<div class="boxed boxed--border clickable-row article" data-href="article?id=<?php echo $row['article_id'] ?>">
										<div class="col-lg-2 col-md-2 col-2" style="display:inline-block;position:relative;top:-40px;">
											<img src="<?php echo $row['cover_image'] ?>" />
										</div>
										<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
											<div class="col-lg-12 col-md-12 col-12">
												<h2 style=""><?php echo $row['title'] ?></h2>
												<h5 style="position:relative;top:-28px;left:10px;margin-bottom:0"><?php echo $row['author'] ?></h5>
												<h6 style="position:relative;top:-38px;left:10px;margin-bottom:0"><?php echo date("F d, Y", strtotime($row['date'])); ?></h6>
											</div>
											<div class="col-lg-12 col-md-12 col-12">
												<span style="position:relative;top:-20px"><?php if(strlen(strip_tags($row['content']))<200){echo mb_substr(strip_tags($row['content']),0,200,'utf-8');}else{ echo mb_substr(strip_tags($row['content']),0,197,'utf-8') . "..."; } ?></span>
											</div>
										</div>
									</div>
								<?php }else if($row['type'] == 'link'){ ?>
									<div class="boxed boxed--border clickable-row article" data-href="<?php echo $row['link'] ?>">
										<div class="col-lg-2 col-md-2 col-2" style="display:inline-block;position:relative;top:-40px;">
											<img src="<?php echo $row['cover_image'] ?>" />
										</div>
										<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
											<div class="col-lg-12 col-md-12 col-12">
												<h2 style=""><?php echo $row['title'] ?></h2>
												<h5 style="position:relative;top:-28px;left:10px;margin-bottom:0"><?php echo $row['author'] ?></h5>
												<h6 style="position:relative;top:-38px;left:10px;margin-bottom:0"><?php echo date("F d, Y", strtotime($row['date'])); ?></h6>
											</div>
											<div class="col-lg-12 col-md-12 col-12">
												<span style="position:relative;top:-20px"><?php if(strlen(strip_tags($row['content']))<200){echo mb_substr(strip_tags($row['content']),0,200,'utf-8');}else{ echo mb_substr(strip_tags($row['content']),0,197,'utf-8') . "..."; } ?></span>
											</div>
										</div>
									</div>
								<?php 
								}
							}
						}else{
							echo "Tonality hasn't uploaded any news yet. Contact us to have your article published";
						}
						$usersqli->close();
						?>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php
            include "footer.php";
            include "script.php";
        ?>
	</body>
</html>