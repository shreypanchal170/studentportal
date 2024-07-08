<?php
	$from = $_GET['from'];
	$to = $_GET['to'];
	$msg = $_GET['msg'];
	if (empty($msg)){
		echo "You can not send empty message";
	}	
	else{
		include 'config.php';	
		$query = "INSERT INTO chat(sent_to,sent_from,message) VALUES ('$to', '$from','$msg')";
		$table = mysqli_query($connection,$query);
		if($table){
			echo "Message sent";
		}
		else{
			echo "Sending failed";
		}
		mysqli_close($connection); // Closing Connection
	}
?>