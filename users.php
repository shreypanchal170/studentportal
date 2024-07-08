<?php
	session_start();
	if (empty($_SESSION['login_user'])){
		header("location: index.php");
		exit();
	}

	error_reporting(0);
	include 'config.php';

	$keyword = $_GET['keyword'];
	$users = "select name from user where (name like '".$keyword."%')";
	$table = mysqli_query($connection,$users);
	if($table){
		$rows=mysqli_num_rows($table);
		if($rows > 0){
			for($x = 0; $x<= $row; $x++){
				$row = mysqli_fetch_assoc($table);
				if ($row){
					$name = $row['name'];
					echo "<option value='$name'></option>";
				}
			}
		}
	}
?>