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
		font-family: calibri;
		overflow:hidden;
	}

	body:hover{
		overflow-y:auto;
	}

	span.user_name{
 		display: block;
 	 	font-size: 15px;
 	 	padding-top: 5px;
 	 	opacity: 0.5;
	}

	img.dp-icon{
  		float: left;
  		height: 45px;
  		width: 45px;
  		margin-right: 15px;
  		margin-bottom: 20px;
  		border-radius: 50%;
  		box-shadow: 1px 2px 5px rgba(100,100,100,0.3);
	}

	ul.contact-list li{
		padding: 15px 20px;
  		border-bottom: 1px solid rgb(245,245,245);
	}

	ul.contact-list li:hover{
		cursor: pointer;
  		background-color: #F3F3F3;
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
	<ul class="contact-list">
		<?php
			$data;
			include 'config.php';
			$query = "call GetContactList(".$_SESSION['login_user'].")";
			$table = mysqli_query($connection,$query);
			if($table){
				$rows=mysqli_num_rows($table);
				if($rows > 0){
					for($x = 0; $x<= $row; $x++){
				    	$row = mysqli_fetch_assoc($table);
				    	if ($row){
		?>
							<li onclick="init(<?php echo $row['id'] ?>, '<?php echo $row['name']?>')">
								<img class="dp-icon" src="<?php echo $row['img_url'];?>"></img>
								<span class="full_name"><?php echo $row['name'];?><span><br/>
								<span class="user_name">@<?php echo $row['user_name'];?></span>
							</li>
		<?php
						}
				    }
				}
			}
		?>
		<script type="text/javascript">
			function init(id,username){
				window.parent.get_contact(id, username); 
				return true;
			}
		</script>
	</ul>


</body>
</html>