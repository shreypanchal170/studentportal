<?php
	include 'config.php';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$full_name = $_POST['reg_name'];
		$user_name = $_POST['reg_uname'];
		$email_id = $_POST['reg_email'];
		$password = $_POST['reg_passwd'];
		$user_type = $_POST['check-type'];

		echo $query = "INSERT INTO user(name,user_name,email_id,password, user_type) VALUES('$full_name','$user_name','$email_id','$password','$user_type')";
   		echo $table = mysqli_query($connection,$query);
    
		if($table){
			$_SESSION['login_user']= $_POST['reg_uname']; 
			header("location: profile.php"); // Redirecting To Other Page
			exit();
		}
		mysqli_close($connection); // Closing Connection
	}
?>