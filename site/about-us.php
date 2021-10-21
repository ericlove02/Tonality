<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
		<style>
		.content-area .profile-link{
			color:black;
			text-decoration: none;
		}
		.content-area .profile-link:hover{
			text-decoration:underline;
		}
		.content-area .instagram{
			float: right;
			width: 30px;
			height: 30px;
			color: black;
			padding: 2px;
		}
		.content-area .instagram:hover{
			color: #cd486b;
		}
		.content-area .website{
			float: right;
			margin-top:-3px;
			margin-right:-2px;
			width: 36px;
			height: 36px;
			color: black;
			padding-top: 2px;
		}
		.content-area .website:hover{
			color: #469FAF;
		}
		.content-area .linkedin{
			float: right;
			width: 30px;
			height: 30px;
			color: black;
			padding: 2px;
		}
		.content-area .linkedin:hover{
			color: #2867B2;
		}
		.content-area .email{
			float: right;
			margin-top:-1px;
			width: 32px;
			height: 32px;
			color: black;
			padding: 2px;
		}
		.content-area .email:hover{
			color: #3a4fd6;
		}
		.content-area .profile{
			display:inline-block;
			margin-bottom: 15px;
			padding-bottom: 15px;
			border-bottom: 1px solid black;
			width: 100%;
			left: 0;
		}
		.content-area .profile:last-child{
			border-bottom: none;
		}
		.content-area .profile-image{
			position: relative;
			width: 200px;
			border-radius: 115px;
			padding: 15px;
			margin-bottom: -20px;
			float: left;
		}
		.content-area .titles{
			margin-top: -12px;
		}
		.content-area .description {
			margin-top: -10px;
		}
		.content-area .account{
			margin-top: -36px;
		}

		.content-area .links{
			color: #569480;
			text-decoration: none;
		}
		.content-area .links:hover{
			text-decoration: underline;
		}
		.content-area .timeline h4{
			margin-top: -16px;
		}
		.content-area .timeline {
		  position: relative !important;
		  max-width: 1200px !important;
		  margin: 0 auto !important;
		}
		.content-area .timeline::after {
		  content: '' !important;
		  position: absolute !important;
		  width: 6px !important;
		  background-color: black !important;
		  top: 0 !important;
		  bottom: 0 !important;
		  left: 1% !important;
		  margin-left: -3px !important;
		}
		.content-area .right {
		  left: 1%;
		}
		/* Add arrows to the right container (pointing left) */
		.content-area .right::before {
		  content: " ";
		  height: 0;
		  position: absolute;
		  top: 22px;
		  width: 0;
		  z-index: 1;
		  left: 30px;
		  border: medium solid #f0e8ce;
		  border-width: 10px 10px 10px 0;
		  border-color: transparent #f0e8ce transparent transparent;
		}

		/* Fix the circle for containers on the right side */
		.content-area .right::after {
		  left: -16px;
		}
		.content-area .container {
		  padding: 10px 40px;
		  position: relative;
		  background-color: white;
		  width: 90%;
		}

		/* The circles on the timeline */
		.content-area .container::after {
		  content: '';
		  position: absolute;
		  width: 25px;
		  height: 25px;
		  right: -17px;
		  background-color: white;
		  border: 4px solid #569480;
		  top: 15px;
		  border-radius: 50%;
		  z-index: 1;
		}
		.content-area .content-timeline {
		  padding: 2px 30px;
		  background-color: #f0e8ce;
		  position: relative;
		  border-radius: 6px;
		}
		</style>
        <div class="main-container">
            <section>
                <div class="container">
                    <div class="row justify-content-left">
                        <div class="col-md-12 col-lg-12">
                            <div class="content-area">
								<h1>About Us</h1>

								Tonality was created by three college students who simply love music and technology. We created Tonality as a safe place for people to share their music taste through ratings, reviews, and discussion to an accepting community. We hope you love Tonality as much as we do!
								<br>
								<h2>Our Team</h2>
								<div class="team">
								<div class="profile">
								<a href="https://www.instagram.com/ericc.love/" target="_blank"><svg class="instagram" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
								  <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
								</svg></a>
								<a href="https://www.linkedin.com/in/ericlove02" target="_blank"><svg class="linkedin" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
								  <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
								</svg></a>
								<a href="mailto:eric@gotonality.com" target="_blank"><svg class="email" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
								  <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
								</svg></a>
								<a href="http://ericlove.online" target="_blank"><svg class="website" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
								  <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
								  <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z"/>
								</svg></a>
								<img class="profile-image" src="images/eric-profile.jpg" />
								<h1 class="name">Eric Love</h1>
								<h3 class="account"><a href="profile?id=2" class="profile-link" target="_blank" style="font-weight:normal">@eric</a></h3>
								<h4 class="titles">Co-Founder / Developer</h4>
								<p class="description">While majoring in Computer Science at Texas A&M University in College Station, Texas, Eric Love has dedicated 600+ hours working to make the Tonality website the best it can be. As Tonality's developer, Eric has designed and coded the front and back end of the website. Eric has experience in numerous coding languages including Python, Java, Swift, and C++, and with this site has gained loads of experience with PHP, HTML, CSS, JavaScript, Ajax, and SQL. Before working on Tonality, Eric had little experience and had even never used a majority of the technologies used to create the site. Even so, he was able to learn and operate as a full stack developer to create a cohesive and functional site, envisioning, designing, and programming the site you see today. For more technical details about the Tonality website, visit his <a href="http://ericlove.online" target="_blank" class="links">website</a>.<br>
								Eric enjoys listening to <a href="artist?id=6sHCvZe1PHrOAuYlwTLNH4" target="_blank" class="links">Gus Dapperton</a>, <a href="artist?id=4V8LLVI7PbaPR0K2TGSxFF" target="_blank" class="links">Tyler, the Creator</a>, <a href="artist?id=0NIPkIjTV8mB795yEIiPYL" target="_blank" class="links">Wallows</a>, and <a href="artist?id=2D4FOOOtWycb3Aw9nY5n3c" target="_blank" class="links">Declan McKenna</a>, however he often mixes in some <a href="artist?id=6PfSUFtkMVoDkx4MQkzOi3" target="_blank" class="links">100 gecs</a>.</p>
								</div>

								<div class="profile">
								<a href="https://www.instagram.com/ben.brody/" target="_blank"><svg class="instagram" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
								  <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
								</svg></a>
								<a href="https://www.linkedin.com/in/benjamin-brody-199904204" target="_blank"><svg class="linkedin" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
								  <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
								</svg></a>
								<a href="mailto:ben@gotonality.com" target="_blank"><svg class="email" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
								  <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
								</svg></a>
								<img class="profile-image" src="images/ben-profile.jpg" />
								<h1 class="name">Ben Brody</h1>
								<h3 class="account"><a href="profile?id=9" class="profile-link" target="_blank" style="font-weight:normal">@banjo</a></h3>
								<h4 class="titles">Co-Founder</h4>
								<p class="description">Ben provides invaluable contributions for site design and functionality, manages marketing strategy, and handles the company's legal matters. Ben is currently pursuing a B.A. in International Studies at American University in Washington, D.C.<br>
								Ben enjoys listening to <a href="artist?id=2h93pZq0e7k5yf4dywlkpM" target="_blank" class="links">Frank Ocean</a>, <a href="artist?id=06HL4z0CvFAxyc27GXpf02" target="_blank" class="links">Taylor Swift</a>, <a href="artist?id=4LLpKhyESsyAXpc4laK94U" target="_blank" class="links">Mac Miller</a>, and <a href="artist?id=07EcmJpfAday8xGkslfanE" target="_blank" class="links">Kevin Abstract</a>. Shockingly, Ben is not a big fan of <a href="artist?id=7pbDxGE6nQSZVfiFdq9lOL" target="_blank" class="links">Rex Orange County</a>.</p>
								</div>

								<div class="profile">
								<a href="https://www.instagram.com/williamkavy/" target="_blank"><svg class="instagram" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
								  <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
								</svg></a>
								<a href="https://www.linkedin.com/in/william-kavy-29883b1bb" target="_blank"><svg class="linkedin" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
								  <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
								</svg></a>
								<a href="mailto:william@gotonality.com" target="_blank"><svg class="email" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
								  <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
								</svg></a>
								<img class="profile-image" src="images/will-profile.jpg" />
								<h1 class="name">William Kavy</h1>
								<h3 class="account"><a href="profile?id=3" class="profile-link" target="_blank" style="font-weight:normal">@kavalicious</a></h3>
								<h4 class="titles">Co-Founder</h4>
								<p class="description">In November 2020, during a search for a website where users can rate their music and discuss with a friendly community, William noticed the lack thereof and approached long-time friend, Eric, with the idea for Tonality. William is currently a freshman at Southern Methodist University in Dallas, Texas studying business in the Cox School of Business.<br>
								William’s favorite music changes regularly, but frequently includes <a href="artist?id=2pAWfrd7WFF3XhVt9GooDL" target="_blank" class="links">MF DOOM</a>, <a href="artist?id=73sIBHcqh3Z3NyqHKZ7FOL" target="_blank" class="links">Childish Gambino</a>, and <a href="artist?id=3AA28KZvwAUcZuOKwyblJQ" target="_blank" class="links">The Gorillaz</a>.</p>
								</div>
								</div>

								<br>
								<h2>Timeline</h2>

								<div class="timeline">
								  <div class="container right">
									<div class="content-timeline">
									  <h2>The Idea<h2>
									  <h4>November 2020</h4>
									  <p>Tonality began when Will brought the idea of "<a href="https://www.rottentomatoes.com/" target="_blank" class="links">Rotten Tomatoes</a> for music" to Eric in October 2020. Eric was very receptive to the idea and immediately began working on it. We initially decided on the name Virtual Record Store, as we wanted it to be a safe place for people to discuss and recommend music. From then on, most discussions for the site were had in xbox parties, as we couldn’t meet in person due to the pandemic.</p>
									</div>
								  </div>
								  <div class="container right">
									<div class="content-timeline">
									  <h2>The First Mock-ups</h2>
									  <h4>November 2020</h4>
									  <p>In November, the first iteration of the VRS logo was created, along with mockups of what we wanted the website to look like.</p>
									  <img src="images/vrs-mockup.jpg" style="width:70%;margin-left:calc(15% - 8px);border-radius:12px;"/>
									  <p>Later, VRS also made its first online appearance, still hosted <a href="https://mass-partition.000webhostapp.com/index" target="_blank" class="links">here</a>. This earliest version was only used as a demo and learning experience, as our developer did not know most of this technology prior to working on Virtual Record Store. The original site required tracks to be manually entered into a database and lacked many of the features you see today.</p>
									</div>
								  </div>
								  <div class="container right">
									<div class="content-timeline">
									  <h2>VRS Takes Shape</h2>
									  <h4>January 2021</h4>
									  <p>By the end of January, Virtual Record Store began to take shape. The site had developed many of its important features, including song rating, reviewing, and following, but still had nagging formatting issues and functionality errors. This stage can be found <a href="http://vrs.rf.gd/index" target="_blank" class="links">here</a>.</p>
									</div>
								  </div>
								  <div class="container right">
									<div class="content-timeline">
									  <h2>VRS &rarr; Tonality</h2>
									  <h4>March 2021</h4>
									  <p>As Virtual Record Store prepared to release, the site was rebranded as Tonality, complete with an aesthetic overhaul.</p>
									  <img src="images/tonality-logo.png" style="width:70%;margin-left:calc(15% - 8px);padding:8px;"/>
									</div>
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
    </body>
</html>