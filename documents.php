<?php
	session_start();
	error_reporting(0);
	if (empty($_SESSION['login_user'])){
		header("location: index.php");
		exit();
	}
?>

<!doctype html>
<html lang="en">
<head>
	<title>Documents</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/style.css"> <!-- Resource style -->
	<link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/profile.css">
	<script src="js/modernizr.js"></script> <!-- Modernizr -->
    <link href="css/jquery.fileuploader.css" media="all" rel="stylesheet">
    <link href="css/jquery.fileuploader-theme-dragdrop.css" media="all" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/jquery.fileuploader.min.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>
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

				<li class="documents active">
					<a href="documents.php">Documents</a>
				</li>

			</ul>

			<ul>
				<li class="cd-label">Secondary</li>
				
				<li class="jobs">
					<a href="jobs.php">Jobs</a>
				</li>

				<li class="settings">
					<a href="settings.php">Settings</a>
				</li>
			</ul>

			<ul>
				<li class="cd-label">Action</li>
				<li class="action-btn"><a href="#">Report Bug</a></li>
			</ul>
			<br/><br/>
			<ul>
				<center>
				<iframe width="100px" height="60px" scrolling="no" src="clock.html"></iframe>
				</center>
			</ul>
		</nav>

		<div class="content-wrapper">
		<form action="documents.php" enctype="multipart/form-data">
   			<input type="file" name="files">
    		<button type="submit" style="padding:15px; margin-left: 15px;">Upload Document</button>
		</form><br/><br/>
		<h2 style="font-size: 25px; margin-left: 15px; padding-bottom: 20px">Uploaded Files</h2>
		<ul>
			<?php
   			// get the name of the folder from url, escape space with %20
   			$url = str_replace("","%20", $_GET['projekt']);
   			$isFolder = is_dir("images/".$projekt."/");
	
   			// folder name on server 
   			$folder = "docs/".$url."/"; 
   			if($isFolder){
      			$data = scandir($folder);
   			} 
   			if($data){
      			foreach ($data as $dat) {
         			$info = pathinfo($folder."/".$dat); 
         			// get image size
         			$size = ceil(filesize($folder."/".$dat)/1024); 
         			if ($dat != "." && $dat != ".." && $dat != "_notes" && $info['extension'] != "") { 
         	?>
            			<li style="margin-left: 15px;background-color: white; max-width: auto; padding: 10px 20px; border: 1px solid rgb(235,235,235); border-left: 2px solid green;"><a href="<?php echo $info['dirname']."/".$info['basename'];?>" title="Download" download><?php echo $info['filename']; ?></a><br><?php echo $info['extension']; ?> | <?php echo $size ; ?> kb </li><br>
       	 			
       	 	<?php 
       	 			};
      			};
    		}else{
        		// this folder is not available
       			echo "<p>Folder not found.</p>";
    		} 
    		?> 
		</ul>
	</main> <!-- .cd-main-content -->
<script src="js/jquery.menu-aim.js"></script>
<script src="js/profile.js"></script> <!-- Resource jQuery -->
</body>
</html>