<!--<div class="loader"></div>-->
<a class="back-to-top inner-link" href="#start" data-scroll-class="100vh:active">
<i class="stack-interface stack-up-open-big"></i>
</a>
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/flickity.min.js"></script>
<script src="js/easypiechart.min.js"></script>
<script src="js/parallax.js"></script>
<script src="js/typed.min.js"></script>
<script src="js/datepicker.js"></script>
<script src="js/isotope.min.js"></script>
<script src="js/ytplayer.min.js"></script>
<script src="js/lightbox.min.js"></script>
<script src="js/granim.min.js"></script>
<script src="js/jquery.steps.min.js"></script>
<script src="js/countdown.min.js"></script>
<script src="js/twitterfetcher.min.js"></script>
<script src="js/spectragram.min.js"></script>
<script src="js/smooth-scroll.min.js"></script>
<script src="js/scripts.js"></script>
<script type="text/javascript" async>
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>
<script>
	function gasVoteClicked(songId){
	    console.log(document.getElementById("gasIcon"+songId).src);
	    if(document.getElementById("gasIcon"+songId).src.includes("images/up-vote.png") && document.getElementById("trashIcon"+songId).src.includes("images/down-vote.png")){
	        document.getElementById("gasIcon"+songId).src = "images/up-voted.png";
	    }else if(document.getElementById("gasIcon"+songId).src.includes("images/up-voted.png") && document.getElementById("trashIcon"+songId).src.includes("images/down-vote.png")){
	        document.getElementById("gasIcon"+songId).src = "images/up-vote.png";
	    }else if(document.getElementById("gasIcon"+songId).src.includes("images/up-vote.png") && document.getElementById("trashIcon"+songId).src.includes("images/down-voted.png")){
	        document.getElementById("gasIcon"+songId).src = "images/up-voted.png";
	        document.getElementById("trashIcon"+songId).src = "images/down-vote.png";
	    }
	}
	function trashVoteClicked(songId){
	    if(document.getElementById("trashIcon"+songId).src.includes("images/down-vote.png") && document.getElementById("gasIcon"+songId).src.includes("images/up-vote.png")){
	        document.getElementById("trashIcon"+songId).src = "images/down-voted.png";
	    }else if(document.getElementById("trashIcon"+songId).src.includes("images/down-voted.png") && document.getElementById("gasIcon"+songId).src.includes("images/up-vote.png")){
	        document.getElementById("trashIcon"+songId).src = "images/down-vote.png";
	    }else if(document.getElementById("trashIcon"+songId).src.includes("images/down-vote.png") && document.getElementById("gasIcon"+songId).src.includes("images/up-voted.png")){
	        document.getElementById("trashIcon"+songId).src = "images/down-voted.png";
	        document.getElementById("gasIcon"+songId).src = "images/up-vote.png";
	    }
	}

	function gasMainVoteClicked(){
		if(document.getElementById("gasIcon").src.includes("images/gas-vote.png") && document.getElementById("trashIcon").src.includes("images/trash-vote.png")){
			document.getElementById("gasIcon").src = "images/gas-voted.png";
		}else if(document.getElementById("gasIcon").src.includes("images/gas-voted.png") && document.getElementById("trashIcon").src.includes("images/trash-vote.png")){
			document.getElementById("gasIcon").src = "images/gas-vote.png";
		}else if(document.getElementById("gasIcon").src.includes("images/gas-vote.png") && document.getElementById("trashIcon").src.includes("images/trash-voted.png")){
			document.getElementById("gasIcon").src = "images/gas-voted.png";
			document.getElementById("trashIcon").src = "images/trash-vote.png";
		}
	}
	function trashMainVoteClicked(){
		if(document.getElementById("trashIcon").src.includes("images/trash-vote.png") && document.getElementById("gasIcon").src.includes("images/gas-vote.png")){
			document.getElementById("trashIcon").src = "images/trash-voted.png";
		}else if(document.getElementById("trashIcon").src.includes("images/trash-voted.png") && document.getElementById("gasIcon").src.includes("images/gas-vote.png")){
			document.getElementById("trashIcon").src = "images/trash-vote.png";
		}else if(document.getElementById("trashIcon").src.includes("images/trash-vote.png") && document.getElementById("gasIcon").src.includes("images/gas-voted.png")){
			document.getElementById("trashIcon").src = "images/trash-voted.png";
			document.getElementById("gasIcon").src = "images/gas-vote.png";
		}
	}
	function reload() {
		location.reload();
	}
	function toggleFollow(select){
		if(select.innerHTML == 'Follow'){
			select.innerHTML = 'Unfollow';
		}else{
			select.innerHTML = 'Follow';
		}
	}
	changeAction = function(select){
	   document.getElementById("search").action = select.value;
	}
</script>