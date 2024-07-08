<?php
	include 'config.php';
	include 'login.php';
	error_reporting(0);
	$query = "call msg_counter(".$_SESSION['login_user'].")";
	$table = mysqli_query($connection,$query);
	if($table){
		echo $rows=mysqli_num_rows($table);
	}
?>