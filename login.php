<?php
	error_reporting(0);
	include 'config.php';
	session_start(); // Starting Session
	$error=''; // Variable To Store Error Message
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST['log_email']) || empty($_POST['log_passwd'])) {
			$error = "Username or Password is empty";
		}
		else {
			$email = $_POST['log_email'];
			$passwd = $_POST['log_passwd'];

			$email = stripslashes($email);
			$passwd = stripslashes($passwd);
			$email = mysql_real_escape_string($email);
			$passwd = mysql_real_escape_string($passwd);

			// SQL query to fetch information of registerd users and finds user match.
			$query = "CALL Login('$email','$passwd')";

			$table = mysqli_query($connection,$query);

			if($table){
				$rows=mysqli_num_rows($table);
				if($rows == 1){
			    	$row = mysqli_fetch_assoc($table);
					$_SESSION['login_user'] = $row['id'];
					$_SESSION['user_name'] = $row['user_name'];
					$_SESSION['name'] = $row['name'];
					$_SESSION['email_id'] = $row['email_id'];
					$_SESSION['user_type'] = $row['user_type'];
					$_SESSION['company_name'] = $row['company_name'];
					$_SESSION['institute_name'] = $row['institute_name'];
					$_SESSION['contact'] = $row['contact_no'];
					$_SESSION['dp_url'] = $row['img_url'];
					setcookie("dp_url", $_SESSION['dp_url']);
					setcookie("user_name", $_SESSION['user_name']);
					header("location: profile.php?user=".$_SESSION['login_user']);

					exit();
				}
				else {
					$error = "Username or Password is invalid";
				}
			} 
			mysql_close($connection); // Closing Connection
		}
		header("location: index.php?error=$error"); // Redirecting To Other Page
	}
?>