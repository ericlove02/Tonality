<?php
include "db_userdata_connect.php";
if (!isset($_SESSION)) {
	session_start();
}
if((!isset($_SESSION['signed_in']) || !$_SESSION['signed_in']) && isset($_COOKIE['un']) && isset($_COOKIE['pw'])){
	$user_name = $_COOKIE['un'];
	$input_password = $_COOKIE['pw'];
	include 'signinuser.php';
}
include "current_song_viewer.php";

$page = substr($_SERVER['REQUEST_URI'], 1);
?>
<iframe name='target' style="display:none"></iframe>
<a id="start"></a>

<!--end bar-->
<div class="notification pos-top pos-right search-box bg--white border--bottom" data-animation="from-top" data-notification-link="search-box">
    <form action="/search">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <input type="search" name="keyword" placeholder="Search . ." />
            </div>
        </div>
        <!--end of row-->
    </form>
</div>

<!--end of notification-->
<div class="nav-container " >
    <!-- <div class="bar bar--sm visible-xs">
        <div class="container">
            <div class="row">
                <div class="col-3 col-md-2">
                    <a href="index">
                        <img class="logo logo-dark" alt="logo" src="images/tonality-logo.png" />
                        <img class="logo logo-light" alt="logo" src="images/tonality-logo.png" />
                    </a>
                    
                </div>
                <div class="col-9 col-md-10 text-right">
                    <a href="#" class="hamburger-toggle" data-toggle-class="#menu1;hidden-xs">
                        <i class="icon icon--sm stack-interface stack-menu"></i>
                    </a>
                    
                </div>
            </div>
            
        </div>
        
    </div> -->
    <!--end bar-->
    <nav id="menu1" class="bar bar--sm bar-1 bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 wid-25">
                    <div class="bar__module dis-flex">
                        <a href="index">
                            <img class="logo logo-dark" alt="logo" src="images/tonality-logo.png" />
							<img class="logo logo-light" alt="logo" src="images/tonality-logo.png" />
                        </a>
                        
                    </div>
                    <!--end module-->
                </div>
                <div class="col-lg-9 col-md-9 text-right text-left-xs text-left-sm col-sm-9 wid-75">
                    <div class="bar__module">
                        <ul class="menu-horizontal text-left">
                            <li>
                                <a href="home">
								<?php if(strpos($page, 'home') === 0){ ?>
									<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
									  <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
									</svg>
								<?php }else{ ?>	
								<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
								  <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
								</svg>
								<?php } ?>
								</a>
                            </li>
							<li class="dropdown">
                                <span class="dropdown__trigger">
								
								<?php if(strpos($page, 'explore') === 0 || strpos($page, 'discover') === 0 || strpos($page, 'genre') === 0 || strpos($page, 'genres') === 0 || strpos($page, 'search-users') === 0  || strpos($page, 'top100') === 0 || strpos($page, 'bottom100') === 0 || strpos($page, 'trending') === 0 || strpos($page, 'most') === 0 || strpos($page, 'gas-of-the-month') === 0 || strpos($page, 'trash-of-the-month') === 0){ ?>
									<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-compass-fill" viewBox="0 0 16 16">
									  <path d="M15.5 8.516a7.5 7.5 0 1 1-9.462-7.24A1 1 0 0 1 7 0h2a1 1 0 0 1 .962 1.276 7.503 7.503 0 0 1 5.538 7.24zm-3.61-3.905L6.94 7.439 4.11 12.39l4.95-2.828 2.828-4.95z"/>
									</svg>
								<?php }else{ ?>	
									<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-compass" viewBox="0 0 16 16">
									  <path d="M8 16.016a7.5 7.5 0 0 0 1.962-14.74A1 1 0 0 0 9 0H7a1 1 0 0 0-.962 1.276A7.5 7.5 0 0 0 8 16.016zm6.5-7.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
									  <path d="M6.94 7.44l4.95-2.83-2.83 4.95-4.949 2.83 2.828-4.95z"/>
									</svg>
								<?php } ?>

								</span>
                                <div class="dropdown__container">
                                    <div class="container">
                                        <div class="row">
                                            <div class="dropdown__content col-lg-2 col-md-4">
                                                <ul class="menu-vertical">
                                                    <li>
                                                        <a href="explore">Explore</a>
                                                    </li>
                                                    <li>
                                                        <a href="discover">Discover Quiz</a>
                                                    </li>
													<li>
                                                        <a href="genres">Genres</a>
                                                    </li>
													<li>
                                                        <a href="search-users">Find Users</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown">
                                <span class="dropdown__trigger">
								
								<?php if(strpos($page, 'community') === 0 || strpos($page, 'category') === 0 || strpos($page, 'topic') === 0 || strpos($page, 'news') === 0 || strpos($page, 'article') === 0){ ?>
									<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-chat-square-quote-fill" viewBox="0 0 16 16">
									  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.5a1 1 0 0 0-.8.4l-1.9 2.533a1 1 0 0 1-1.6 0L5.3 12.4a1 1 0 0 0-.8-.4H2a2 2 0 0 1-2-2V2zm7.194 2.766a1.688 1.688 0 0 0-.227-.272 1.467 1.467 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 5.734 4C4.776 4 4 4.746 4 5.667c0 .92.776 1.666 1.734 1.666.343 0 .662-.095.931-.26-.137.389-.39.804-.81 1.22a.405.405 0 0 0 .011.59c.173.16.447.155.614-.01 1.334-1.329 1.37-2.758.941-3.706a2.461 2.461 0 0 0-.227-.4zM11 7.073c-.136.389-.39.804-.81 1.22a.405.405 0 0 0 .012.59c.172.16.446.155.613-.01 1.334-1.329 1.37-2.758.942-3.706a2.466 2.466 0 0 0-.228-.4 1.686 1.686 0 0 0-.227-.273 1.466 1.466 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 10.07 4c-.957 0-1.734.746-1.734 1.667 0 .92.777 1.666 1.734 1.666.343 0 .662-.095.931-.26z"/>
									</svg>
								<?php }else{ ?>	
									<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-chat-square-quote" viewBox="0 0 16 16">
									  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
									  <path d="M7.066 4.76A1.665 1.665 0 0 0 4 5.668a1.667 1.667 0 0 0 2.561 1.406c-.131.389-.375.804-.777 1.22a.417.417 0 1 0 .6.58c1.486-1.54 1.293-3.214.682-4.112zm4 0A1.665 1.665 0 0 0 8 5.668a1.667 1.667 0 0 0 2.561 1.406c-.131.389-.375.804-.777 1.22a.417.417 0 1 0 .6.58c1.486-1.54 1.293-3.214.682-4.112z"/>
									</svg>
								<?php } ?>
								
								</span>
                                <div class="dropdown__container">
                                    <div class="container">
                                        <div class="row">
                                            <div class="dropdown__content col-lg-2 col-md-4">
                                                <ul class="menu-vertical">
                                                    <li>
                                                        <a href="community">Forums</a>
                                                    </li>
                                                    <li>
                                                        <a href="news">News</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php if (isset($_SESSION['userID'])) { 
                                $result = $usersqli->query("SELECT user_image, first_name FROM data WHERE userID=\"" . $_SESSION['userID'] . "\"");
                                $usersqli->close();
                                if ($result->num_rows > 0) {
                                    
                            ?>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                            <li class="dropdown">
                                <img  src="<?php echo $row["user_image"] ?>" style="border-radius:5px;width:34px;height:34px;margin-bottom:0px" class="dropdown__trigger" />
                                <div class="dropdown__container">
                                    <div class="container">
                                        <div class="row">
                                            <div class="dropdown__content col-lg-2 col-md-4">
                                                <ul class="menu-vertical">
                                                    <li>
                                                        <a href="profile?id=<?php echo $_SESSION['userID'] ?>">Profile</a>
                                                    </li>
                                                    <li>
                                                        <a href="profile-notifications">Notifcations</a>
                                                    </li>
                                                    <li>
                                                        <a href="signout.php">Log Out</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php }  ?>
                            <?php }  ?>
                            <?php } else { ?>
                            <li>
                                <a href="login"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
								  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
								  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
								</svg></a>
                            </li>
                            <?php }  ?>
							<li>
                                <a href="#" data-notification-link="search-box">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
									  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
									</svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--end of row-->
        </div>
        <!--end of container-->
    </nav>
    <!--end bar-->
</div>