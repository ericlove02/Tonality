<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>

<body class="">
<?php include "navbar.php"; ?>

<?php if($_SESSION['user_level'] >= 2){ ?>

<h1 style="width:550px;margin: 20px auto">NEWS DASHBOARD</h1>
<div style="width:50%;margin: 0 auto;">
<a href="mod_dashboard.php">Back to Moderator Dashboard</a>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
			<div class="boxed boxed--border">
			
				<form action="do_add_article.php" method="POST">
					<div class="col-md-5" style="display:inline-block">
						<span>Type:</span>
						<select name="type" id="dropdown">
							<option value="article">Article</option>
							<option value="link">Link</option>
					    </select>
					</div>
					<div class="col-md-6" style="display:inline-block">
						<span>Author:</span>
						<input type="text" name="author" />
					</div>
					<div class="col-md-11" style="display:inline-block">
						<span>Title:</span>
						<input type="text" name="title" />
					</div>
					<div class="col-md-6" style="display:inline-block">
						<span>Link to cover (get from Imgur.com, be sure to end in .png):</span>
						<input type="text" name="cover-link" />
					</div>
					<div class="col-md-5" style="display:inline-block">
						<span>Link (if link, else leave blank):</span>
						<input type="text" name="link" />
					</div>
					
					<div class="col-md-12" style="display:inline-block">
						<span>Content (leave blank if link or you can copy the first paragraphs to show on preview):</span>
						<textarea name="content" id="content"></textarea>
					</div>
					<div class="col-md-8 text-center" style="display:inline-block">
						<input type="submit" />
					</div>
				</form>
			
			</div>
		</div>
	</div>
</div>

<?php }else{ ?>
<br>
<br>
<p style="padding: 20px;margin-left:25%">This page is restricted to Tonality Moderators only</p>
<?php 
}

include "footer.php";
include "script.php";
?>
<script src="https://cdn.tiny.cloud/1/40hct26l99thitiqzooduhp5jwcna4y5o9mgb5axydeeiom6/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
	tinymce.init({
	  selector: '#content',
	  height: 600,
	  skin: 'outside',
	  icons: 'thin',
	  toolbar_location: 'bottom',
	  menubar: false,
	  plugins: [
		'advlist autolink lists link charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste imagetools wordcount',
		'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons'
	  ],
	  toolbar: 'paste | undo redo | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | bold italic link underline strikethrough | insertfile image media pageembed template link anchor codesample | preview fullscreen ', 
	  placeholder: 'Content here...'
	});
</script>

</body>
</html>