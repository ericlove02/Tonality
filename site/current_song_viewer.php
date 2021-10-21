<style>

	.current-song-viewer {
		border-radius: 10px;
		background-color: white;
		position: fixed;
		bottom: -5px;
		right: 8%;
		border: 1px solid #ececec;
		z-index: 1;
		overflow: hidden;
		width: 340px;
		height: 200px;
	}
	.song-display{
		width: 340px;
		height: 200px;
	}
	.queueIcon {
		position: absolute;
		height: 20px;
		width: 20px;
		top: 6px;
		right: 30px;
	}
	.queueIcon:hover {
		color: red;
	}
	.queue {
		border-radius: 10px;
		background-color: white;
		position: fixed;
		bottom: 210px;
		right: 2%;
		border: 1px solid #ececec;
		z-index: 1;
		overflow: hidden;
		width: 340px;
		height: 522px;
	}
	.queue-display{
		width: 340px;
		height: 522px;
	}
	.current-song-viewer .closeview{
		color:red;
		position: absolute;
		height: 32px;
		width: 32px;
		top: 0;
		right: 0;
	}


.viewer-closed{
	border-radius: 10px;
	background-color: white;
	position: fixed;
	bottom: -5px;
	right: 8%;
	width: 260px;
	height: 40px;
	border: 1px solid #ececec;
	font-size: 14px;
	z-index: 1;
}
.viewer-closed{
	cursor: pointer;
}
.viewer-closed p {
	margin: 10px;
	margin-top: 4px;
}
.current-song-viewer .closeview:hover{
	cursor: pointer;
}
iframe{
	overflow: hidden;
}

</style>
<div class="viewer-closed" id="current-song-viewer-closed" onClick="openViewer()">
<p>Vote while you listen on Spotify &#8593;</p>
</div>
<div class="current-song-viewer" id="current-song-viewer" style="display:none">
	<div class="current-song-content">
		<svg class="closeview" onClick="closeViewer()" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
		  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
		</svg>
		<?php 
		if(!isset($_SESSION)){
			session_start();
		}
		if(isset($_SESSION['spotify_connected']) && $_SESSION['spotify_connected'] && isset($_SESSION['isPremium']) && $_SESSION['isPremium']){ 
		 ?>
		
		<svg class="queueIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note-list" viewBox="0 0 16 16" onmouseover="showQueue(); refreshIframe2()" onmouseout="hideQueue()">
		  <path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2z"/>
		  <path fill-rule="evenodd" d="M12 3v10h-1V3h1z"/>
		  <path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1V2.82z"/>
		  <path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5z"/>
		</svg>
		<?php } ?>
		<?php 
		include "api_connect.php";
		if(isset($_SESSION['token'])){ ?>
			<iframe src="current_song.php" class="song-display" id="iframe" frameBorder="0"></iframe>
		<?php }else{ ?>
			<iframe src="spotify_button.php" class="song-display" id="iframe" frameBorder="0"></iframe>
		<?php } ?>
	</div>
</div>
<div class="queue" id="queue" style="display:none">
<iframe src="queue.php" id="iframe2" class="queue-display" frameBorder="0"></iframe>
</div>
<script type="text/javascript">
function openViewer(){
	document.getElementById('current-song-viewer').style = "";
	document.getElementById('current-song-viewer-closed').style = "display:none";
	//console.log('open');
	window.open('playbackFunctions/set_playback_open.php', 'target');
}
function closeViewer(){
	document.getElementById('current-song-viewer').style = "display:none";
	document.getElementById('current-song-viewer-closed').style = "";
	//console.log('close');
	window.open('playbackFunctions/set_playback_closed.php', 'target');
}
function refreshIframe() {
    document.getElementById('iframe').contentWindow.location.reload();
}
function refreshIframe2() {
	document.getElementById('iframe2').contentWindow.location.reload();
}
//setInterval(refreshIframe, 10000);

function showQueue(){
	document.getElementById('queue').style = "";
}
function hideQueue(){
	document.getElementById('queue').style = "display:none";
}
<?php if(isset($_SESSION['playbackIsOpen']) && $_SESSION['playbackIsOpen']){  ?>
	document.getElementById('current-song-viewer').style = "";
	document.getElementById('current-song-viewer-closed').style = "display:none";
<?php } ?>
</script>