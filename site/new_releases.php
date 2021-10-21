<?php
	include "api_connect.php";
	$results = $api->getNewReleases(['limit' => 30]);

?>
<style type="text/css">
	.is-hidden {
	 	display: none;
	}
</style>
<section class="padding-bottom-0" style="<?php if($isMobile){ echo 'margin-top:-100px'; } ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-12">
				<?php if(!$isMobile){ echo "<h3 style='margin-bottom:0;margin-top:-60px'>New Releases</h3>"; } ?>
				<div class="slider slider--columns is-hidden" data-arrows="false" data-paging="true" style="<?php if($isMobile){ echo 'margin-bottom:80px;width:100vw;margin-left:-60px'; } ?>">

					<ul class="slides">
						<?php $index = 0;
						foreach ($results->albums->items as $album) { 
							$index++; ?>
							<li class="col-md-3 col-3">
								<a class="clickable-row" data-href="album?id=<?php echo substr($album->uri, -22)  ?>">
									<img alt="Cover Image" src="<?php echo $album->images[0]->url; ?>" />
								</a>
								<!--<p style="font-size:12px"><?php //echo $album->name; ?></p>-->
							</li>
						<?php 
						}?>
					</ul>
				</div>
			</div>
			<!--end of col-->
		</div>
		<!--end row-->
	</div>
	<!--end of container-->
</section>
<div style="margin-bottom: -110px;"></div>
