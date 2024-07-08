<?php
	error_reporting(0);
	session_start();
	include 'config.php';
	if (empty($_SESSION['login_user'])){
		header("location: index.php");
		exit();
	}

	if (isset($_GET['add-contact'])){
		if ($_GET['add-contact'] == 0){
			$add = "INSERT INTO `contact`(`user_id`, `contact_id`) VALUES (".$_SESSION['login_user'].",".$_GET['user'].")";
			$added = mysqli_query($connection,$add);
		}
	}
	header("location: profile.php?user=".$_GET['user']);
?>