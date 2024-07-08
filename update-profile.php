<?php
	include 'config.php';
	session_start();
	if (empty($_SESSION['login_user'])){
		header("location: index.php");
		exit();
	}

	$user = $_SESSION['login_user'];

	if (($_GET['institute'] != "select-inst") && ($_GET['degree'] != "select-deg") && ($_GET['from_year'] != "year") && ($_GET['to_year'] != "year")){
		$inst = $_GET['institute'];
		$deg = $_GET['degree'];
		$from = $_GET['from_year'];
		$to = $_GET['to_year'];
		$query = "INSERT INTO has_degree(student_id, degree_id, verified, from_year, to_year, institute_id) VALUES ($user,$deg,0,$from,$to,$inst)";
		$table = mysqli_query($connection,$query);

	}

	if (!empty($_GET['skill'])){
		$skill = $_GET['skill'];
		$query = "INSERT INTO has_skill(student_id, skill) VALUES ('$user','$skill')";
		$table = mysqli_query($connection,$query);
	}

	if(!empty($_GET['interest'])){
		$interest = $_GET['interest'];
		$query = "INSERT INTO has_interest(student_id, interest) VALUES ('$user','$interest')";
		$table = mysqli_query($connection,$query);
	}
	header("location: profile.php?user=".$_SESSION['login_user']);
	mysqli_close($connection); // Closing Connection
?>
