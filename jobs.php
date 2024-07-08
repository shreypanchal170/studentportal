<?php
	include 'login.php';
	if (empty($_SESSION['login_user'])){
		header("location: index.php");
		exit();
	}
?>

<!doctype html>
<html lang="en">
<head>
	<title>Jobs</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/style.css"> <!-- Resource style -->
	<link rel="stylesheet" href="css/media.css">
	<link rel="stylesheet" href="css/jobs.css">
	
	<script src="js/modernizr.js"></script> <!-- Modernizr -->
</head>
<body onload="check()">
	<script type="text/javascript">
		function check(){
			$("span#msgcount").load("msg-counter.php");
		}

		function suggestions(){
			var input = $(".search").val();
			var url = "users.php?keyword="+input;
			$("datalist#searches").load(url);
		}

		function searched(){
			var name = $(".search").val();
			window.open("profile.php?user="+name,'_self');
		}
	</script>
	<header class="cd-main-header">
		<a href="profile.php" class="cd-logo"><img src="images/logo.png" alt="Logo"></a>
		
		<div class="cd-search is-hidden">
				<input list="searches" class="search" placeholder="Search..." onkeyup="suggestions()" onchange="searched()">
				<datalist id="searches">
				</datalist>
		</div> <!-- cd-search -->

		<a href="#" class="cd-nav-trigger">Menu<span></span></a>

		<nav class="cd-nav">
			<ul class="cd-top-nav">
				<!--<li><a href="#">Privacy Policy</a></li>-->
				<li class="has-children account">
					<a href="#">
						<img src="<?php echo $_SESSION['dp_url'];?>">
						Account
					</a>
					<ul>
						<li><a href="settings.php">Edit Account</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</header> <!-- .cd-main-header -->

	<main class="cd-main-content">
		<nav class="cd-side-nav">
			<ul>
				<li class="nav-dp"><a class="dp"><img src="<?php echo $_SESSION['dp_url'];?>"></a></li>
				<li class="nav-name"><h1>@<?php echo $_SESSION['user_name'];?></h1></li>
				<li class="cd-label">Main</li>
				<li class="profile">
					<a href="profile.php?user=<?php echo $_SESSION['login_user'];?>">Profile</a>
				</li>

				<li class="messages">
					<a href="inbox.php">Messages<span class="count" id="msgcount"></span></a>
				</li>

				<li class="documents">
					<a href="documents.php">Documents</a>
				</li>

			</ul>

			<ul>
				<li class="cd-label">Secondary</li>
				
				<li class="jobs active">
					<a href="jobs.php">Jobs</a>
				</li>

				<li class="settings">
					<a href="settings.php">Settings</a>
				</li>
			</ul>

			<ul>
				<li class="cd-label">Action</li>
				<li class="action-btn"><a href="#">Report Bug</a></li>
			</ul><br/><br/>
			<ul>
				<center>
				<iframe width="100px" height="60px" scrolling="no" src="clock.html"></iframe>
				</center>
			</ul>
		</nav>

		<div class="content-wrapper">
        	<div class="profile-container" style="width:60%;float:left;height:100%;">
            <h2 class="heading"> Job Title </h2>
            	<ul class= "sorter"> 
           			<li>Company, Location</li>
                    <li>Description</li>
            	</ul>
        	</div>
            <div class="profile-container" style="width:60%;float:left;height:100%;">
            <h2 class="heading"> Job Title </h2>
            	<ul class= "sorter"> 
           			<li>Company, Location</li>
                    <li>Description</li>
            	</ul>
        	</div>
            <div class="profile-container" style="width:60%;float:left;height:100%;">
            <h2 class="heading"> Job Title </h2>
            	<ul class= "sorter"> 
           			<li>Company, Location</li>
                    <li>Description</li>
            	</ul>
        	</div>
            <div class="profile-container" style="width:60%;float:left;height:100%;">
            <h2 class="heading"> Job Title </h2>
            	<ul class = "sorter"> 
           			<li>Company, Location</li>
                    <li>Description</li>
            	</ul>
        	</div>
            <div class="profile-container" style="width:30%; margin-left:5%">
            <h2 class="heading">Filter by </h2>
            	<ul class= "sorter">
                	<a href="#"><li>&gt;Location</li></a>
                    <a href="#"><li>&gt;Salary</li></a>
                    <a href="#"><li>&gt;Company</li></a>
            	</ul>
            </div>
            <div class="profile-container" style="width:30%; margin-left:5%">
                <h2 class="heading">Sort by </h2>
                <ul class= "sorter">
                	<a href="#"><li>&gt;Name</li></a>
                    <a href="#"><li>&gt;Salary</li></a>
                    <a href="#"><li>&gt;Duration</li></a>
            	</ul>
            </div>
		</div> <!-- .content-wrapper -->
	</main> <!-- .cd-main-content -->
<script src="js/jquery.js"></script>
<script src="js/jquery.menu-aim.js"></script>
<script src="js/profile.js"></script> <!-- Resource jQuery -->
</body>
</html>