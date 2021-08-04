<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body style="" class="">
		<?php include "navbar.php";  ?>
		<div class="main-container">
			<section class="padding-bottom-0">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
						<?php
						$article_id = $_GET['id'];
						include "db_userdata_connect.php";
						$article = $usersqli->query("SELECT * FROM articles WHERE article_id=".$article_id);
						if($article->num_rows > 0){
							$row = $article->fetch_assoc();
							?>
							<div class="boxed boxed--border">
								<div class="col-lg-12 col-md-12 col-12" style="border-bottom:2px solid black;padding-bottom:0">
									<div class="col-lg-1 col-md-1 col-1" style="display:inline-block;position:relative;top:-96px;">
										<img src="images/tonality-icon.png" style="margin-bottom:-64px;"/>
									</div>
									<div class="col-lg-9 col-md-9 col-9" style="display:inline-block">
										<div class="col-lg-12 col-md-12 col-12">
											<h2 style=""><?php echo $row['title'] ?></h2>
											<h5 style="position:relative;top:-28px;left:10px;margin-bottom:0"><?php echo $row['author'] ?></h5>
											<h6 style="position:relative;top:-38px;left:10px;margin-bottom:0"><?php echo date("F d, Y", strtotime($row['date'])); ?></h6>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-12">
									<div style="width:90%;margin: 10px auto"><?php echo $row['content']; ?></div>
								</div>
							</div>
						<?php } ?>
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