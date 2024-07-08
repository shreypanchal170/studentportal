<?php
	error_reporting(0);
	session_start();
	if (empty($_SESSION['login_user'])){
		header("location: index.php");
		exit();
	}
?>

<!doctype html>
<html lang="en">
<head>
	<title>Inbox</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Fjalla+One|Maitree|Ropa+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	<link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/style.css"> <!-- Resource style -->
	<link rel="stylesheet" href="css/media.css">
	<script src="js/modernizr.js"></script> <!-- Modernizr -->
	<style>
		.cd-main-content .content-wrapper {
  			padding: 35px 5px 0 0;
		}
		@media only screen and (min-width: 768px) {
  			.cd-main-content .content-wrapper {
    			margin-top: 5px;
    			padding-top: 25px;
  			}
  		}
  		.cd-main-content::after{
  			background-image: none;
  		}
  		button#send{
  			float: right;
  			width: 70px;
  		}
  		.type-msg input{
  			width: 65%;
  		}
	</style>
	<script type="text/javascript">
		function get_contact(id, username) { 
			//location.href = "inbox.php?user="+id;
    		$(".msg .msg-head .heading").html(username);
    		var url = "msg-list.php?from="+id;
    		$("iframe#msg-list").prop("src",url);
		}
	</script>
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
	<div class="msg-alert"><p></p></div>
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

				<li class="messages active">
					<a href="inbox.php">Messages<span class="count" id="msgcount"></span></a>
				</li>

				<li class="documents">
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
			</ul><br/><br/>
			<ul>
				<center>
				<iframe width="100px" height="60px" scrolling="no" src="clock.html"></iframe>
				</center>
			</ul>
		</nav>

		<div class="content-wrapper">
			<div class="inbox-container">
				<div class="contacts">
					<div class="contact-head">
						<h2 class="heading">Inbox</h2>
					</div>	<!-- contact-head -->
					<div class="contact-body">
						<iframe name="contact-list" src="contact-list.php"></iframe>
					</div>	<!-- contact body -->
				</div>	<!-- contacts -->

				<div class="msg">
					<div class="msg-head">
						<h2 class="heading">
							Select a contact.
						</h2>
					</div>
					<div class="msg-body">
						<iframe id="msg-list" name="msg-list" width="100%"></iframe>
					</div>
					<div class="type-msg">
						<input autofocus="" autocomplete="off" type="text" class="input" name="sent-msg" id="sent-msg" placeholder="Type a message...">
						<button id="send" name="send">Send</button>
					</div>
				</div>
			</div>
		</div> <!-- .content-wrapper -->
	</main> <!-- .cd-main-content -->
<script src="js/jquery.js"></script>
<script src="js/jquery.menu-aim.js"></script>
<script src="js/profile.js"></script> <!-- Resource jQuery -->
<script type="text/javascript">
	$("button#send").click(function(){
		var to = document.getElementById("msg-list").src;
		to = to.substr(46);		//using http://asadali.com/istudent/msg-list.php?user=*
	
		var msg = $(".type-msg input").val();

		<?php
  				$from = $_SESSION['login_user'];
		?>
		 $.ajax({
            url: "send-msg.php?from=<?php echo $from ?>&to="+to+"&msg="+msg,
            success: function(data){
            	$(".msg-alert p").html(data);
            	if (data == "Message sent"){
            		$(".msg-alert p").css("background-color","green");
            	}
            	else{
            		$(".msg-alert p").css("background-color","red");
            	}
            	$(".msg-alert").show(0,function(){
            		$(".msg-alert").delay(2000).fadeOut('slow');
            	});
            	$(".type-msg input").val("");
            	document.getElementById("msg-list").contentDocument.location.reload(true);
             }
          });
	});
    
    setInterval(function(){ 
    	document.getElementById("msg-list").contentDocument.location.reload(true);
    }, 10000);
</script>

</body>
</html>