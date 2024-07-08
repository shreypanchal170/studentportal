<?php
	include 'login.php';
	if (empty($_SESSION['login_user'])){
		header("location: index.php");
		exit();
	}
?>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Fjalla+One|Maitree|Ropa+Sans" rel="stylesheet">

	<link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/style.css"> <!-- Resource style -->
	<link rel="stylesheet" href="css/media.css">
	<script src="js/modernizr.js"></script> <!-- Modernizr -->
<style>
	body{
		font-size: 20px;
		font-family: 'Ropa Sans';
		overflow:hidden;
	}

	body:hover{
		overflow-y:auto;
	}
	ul.msg-list li{
		padding: 12px 20px;
  		border-radius: 20px;
  		border:none;
	}

	body::-webkit-scrollbar-track {
		border-radius: 10px;
	}

	body::-webkit-scrollbar {
  		width: 8px;
		background-color: #F5F5F5;
	}

	body::-webkit-scrollbar-thumb {
  		border-radius: 10px;
		background-color: rgb(180,180,180);
	}

</style>
</head>
<body>
	<ul class="msg-list">
		<?php
			include 'config.php';
			$from = $_GET['from'];
			$query = "call GetMsgList(".$from.",".$_SESSION['login_user'].")";
			$table = mysqli_query($connection,$query);
			if($table){
				$rows=mysqli_num_rows($table);
				if($rows > 0){
					for($x = 0; $x<= $row; $x++){
						$row = mysqli_fetch_assoc($table);
						if ($row['sent_from']==$_SESSION['login_user']){
		?>
							<li id="sent" style="font-family: Helvetica;font-size: 14px; background-color: #0ebc7f; color: white; margin:2px 10px; float: right;clear: both; max-width: 70%; line-height: 20px;">
		<?php
							echo $row['message'];

						}
		?>
							</li>
		<?php
						if ($row['sent_to']==$_SESSION['login_user']){
		?>
							<li id="received" style="font-family: Helvetica;font-size: 14px; background-color: #F0F0F0; margin:2px 10px; float: left;clear: both;max-width: 70%;line-height: 20px;">
		<?php
							echo $row['message'];
						}	
		?>
							</li>
		<?php
					}
				}
			}
		?>
	</ul>

</body>
</html>