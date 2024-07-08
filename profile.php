<?php
	error_reporting(0);
	include 'config.php';
	session_start();
	if (empty($_SESSION['login_user'])){
		header("location: index.php");
		exit();
	}
	
	if (is_numeric($_GET['user'])){
		$query = "select name,email_id,contact_no,img_url,user_type from user where id=".$_GET['user'];
	}
	else{
		$query = "select name,email_id,contact_no,img_url,user_type from user where name='".$_GET['user']."'";
	}

	$table = mysqli_query($connection,$query);
		if($table){
			$rows=mysqli_num_rows($table);
			if($rows == 1){
		    	$row = mysqli_fetch_assoc($table);
		    	$dp = $row['img_url'];
		    	$fullname = $row['name'];
		    	$email = $row['email_id'];
		    	$contact = $row['contact_no'];
		    }
		}	
?>

<!doctype html>
<html lang="en">
<head>
	<title>Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Fjalla+One|Maitree|Ropa+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	<link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/style.css"> <!-- Resource style -->
	<link rel="stylesheet" href="css/media.css">
	<link rel="stylesheet" href="css/profile.css">
	<script src="js/modernizr.js"></script> <!-- Modernizr -->

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
				<li class="nav-dp"><a class="dp"><img src="<?php echo $_COOKIE['dp_url'];?>"></a></li>
				<li class="nav-name"><h1>@<?php echo $_COOKIE['user_name'];?></h1></li>
				<li class="cd-label">Main</li>
				<li class="profile active">
					<a href="profile.php?user=<?php echo $_SESSION['login_user'];?>">Profile</a>
				</li>

				<li class="messages">
					<a href="inbox.php">Messages<span class="count" id="msgcount"></span></a>
				</li>

				<li class="has-children documents">
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
			<div class="profile-container">
				<div class="info-box">
					<img id="pro-timeline" src="images/pro-timeline.jpg"></img>
					<div class="pro-title">
	            		<center><img class="profile-image" src="<?php echo $dp; ?>">
	            		 <p id="pro-name"><?php echo $fullname; ?></p>
	            		 <span class="pro-detail"><?php echo $email; ?></span> | <span class="pro-detail" id="number"><?php echo $contact; ?></span><br/><br/>
	            		 <form action="add-contact.php">
	            		 <input value="<?php echo $_GET['user'];?>" id="user" name="user" hidden>
	            		 <button type="submit" style="padding: 10px;" id="add-contact" name="add-contact" value="0">Add to Contact List</button></form>
	            		 </center>
	            	</div>
                </div>
	            <div class="pro-education student">
    	        	<h1 class="heading">Education<button id="add-edu" class="add-button remove">+ Add New</button></h1>
    	        	<div class="pro-education-body pro-body">
    	        	<center>
	            	    <form id="form-edu" hidden="" class="remove" action="update-profile.php">
	            	   		<select id="institute" name="institute" onchange="fill_degree()">
	            	   			<option value="select-inst">Select Institute</option>
	            	   			<?php
	            	   				$query = "select * from user where user_type='institute'";
	            	   				$table = mysqli_query($connection,$query);
									if($table){
										$rows=mysqli_num_rows($table);
										if($rows > 0){
											for($x = 0; $x<= $row; $x++){
				    							$row = mysqli_fetch_assoc($table);
				    							if ($row){
								?>		
													<option value="<?php echo $row['id']; ?>"><?php echo $row['name'] ?></option>
								<?php
												}
											}
										}
									}
	            	   			?>
	            	   		</select>
	            	   		<br/>
	            	   		<select id="degree" name="degree" required="">
	            	   			<option value="select-deg">Select Degree</option>
	            	   			<script>
	            	   				function fill_degree(){
	            	   					var selected = $("select#institute").val();
	            	   					$("select#degree").load("degree.php?institute="+selected);
	            	   					
	            	   				}
	            	   			</script>
	            	   		</select>
	            	   		<br/>
	            	    	<select id="from_year" name="from_year">
	            	    		<option value="year">From Year</option>
  								<script>
  									var myDate = new Date();
  									var year = myDate.getFullYear();
  									for(var i = year; i > 1980; i--){
	  									document.write('<option value="'+i+'">'+i+'</option>');
  									}
  								</script>
							</select><br/>
							<select id="to_year" name="to_year">
	            	    		<option value="year">To Year (or Expected)</option>
  								<script>
  									var myDate = new Date();
  									var year = myDate.getFullYear();
  									for(var i = year+5; i > 1990 ; i--){
	  									document.write('<option value="'+i+'">'+i+'</option>');
  									}

  								</script>
							</select><br/><br/>
		                	&nbsp;<button id="item-submit-edu" class="edit-button">Save</button>
		                	<button type="reset" id="cancel-edu" class="edit-button cancel">Cancel</button><br/><br/>
	    	            </form>
	    	            </center>
	               		<ul class="education-list">
	               		<?php
							$query = "SELECT * FROM `has_degree` join `user` on (student_id = id) where student_id = ".$_GET['user'];
							$table = mysqli_query($connection,$query);
							if($table){
								$rows=mysqli_num_rows($table);
								if($rows > 0){
									for($x = 0; $x<= $row; $x++){
				    					$row = mysqli_fetch_assoc($table);
				    					if ($row){
						?>
											<li>
						<?php
												$inst_query = "select name from user where id = ".$row['institute_id'];
												$inst_table = mysqli_query($connection, $inst_query);
												if ($inst_table){
													$inst_column = mysqli_fetch_assoc($inst_table);
													echo "<p style='padding-bottom: 10px; font-size:18px; font-weight:bold'>".$inst_column['name']."</p>";
												}

												$deg_query = "select degree_name from degree where degree_id = ".$row['degree_id'];
												$deg_table = mysqli_query($connection, $deg_query);
												if ($deg_table){
													$deg_column = mysqli_fetch_assoc($deg_table);
													echo "<p style='padding-bottom: 10px;'>".$deg_column['degree_name']."</p>";
												}
						?>
												<p style="font-size: 13px;"><span><?php echo $row['from_year']; ?></span> - <span><?php echo $row['to_year']; ?></span></p>
											</li>

						<?php
										}
				    				}
								}
							}
						?>
		                </ul>
	            	</div>    
	            </div>
	            <div class="pro-skill student">
	            	<h1 class="heading">Skills<button id="add-ski" class="add-button remove">+ Add New</button></h1>
	            	<div class="pro-skill-body pro-body">	
		                <center><form id="form-ski" hidden="" class="remove" action="update-profile.php">
		                	<input type="text" id="skill" name="skill" required="" placeholder="Skill (ex: Data Analysis)" ><br><br/>
		                	&nbsp;<button id="item-submit-ski" class="edit-button">Add</button>
	    	            	<button type="reset" id="cancel-ski" class="edit-button cancel">Cancel</button><br/><br/>
    	        	    </form></center>      		
		                <ul class="skill-list">
	               		<?php
							$query = "SELECT * FROM `has_skill` join `user` on student_id = id where student_id = ".$_GET['user'];
							$table = mysqli_query($connection,$query);
							if($table){
								$rows=mysqli_num_rows($table);
								if($rows > 0){
									for($x = 0; $x<= $row; $x++){
				    					$row = mysqli_fetch_assoc($table);
				    					if ($row){
						?>
											<li>
												<span class="ski-span"><?php echo $row['skill'];?></span>
											</li>

						<?php
										}
				    				}
								}
							}
						?>		                
		                </ul>
    	            </div>
    	        </div>
    	        <div class="pro-interest student">
    	        	<h1 class="heading">Interests<button id="add-int" class="add-button remove">+ Add New</button></h1>
    	        	<div class="pro-interest-body pro-body">
    		            <center><form id="form-int" hidden="" class="remove" action="update-profile.php">
    	    	        	<input type="text" id="interest" name="interest" required="" placeholder="Interest (ex: Reading)" ><br><br/>
    	        	    	&nbsp;<button id="item-submit-int" class="edit-button">Add</button>
    	            		<button type="reset" id="cancel-int" class="edit-button cancel">Cancel</button><br/><br/>
    	            	</form></center>
	    	            <ul class="interest-list">
	               		<?php
							$query = "SELECT * FROM `has_interest` join `user` on student_id = id where student_id = ".$_GET['user'];
							$table = mysqli_query($connection,$query);
							if($table){
								$rows=mysqli_num_rows($table);
								if($rows > 0){
									for($x = 0; $x<= $row; $x++){
				    					$row = mysqli_fetch_assoc($table);
				    					if ($row){
						?>
											<li>
												<span class="int-span"><?php echo $row['interest'];?></span>
											</li>
						<?php
										}
				    				}
								}
							}
						?>		                
						</ul>
    	            </div>
    	        </div>
            </div> <!-- profile-container -->
		</div> <!-- .content-wrapper -->
	</main> <!-- .cd-main-content -->
<script src="js/jquery.js"></script>
<script src="js/jquery.menu-aim.js"></script>
<script src="js/profile.js"></script> <!-- Resource jQuery -->
<script>
$(document).ready(function(){
	$("button#add-contact").hide();
	<?php
		if ($_GET['user'] != $_SESSION['login_user']){
			$_SESSION['added'] = 0;
	?>
			$(".remove").hide();
			$("button#add-contact").show();
	<?php
			$query = "select * from contact where user_id =".$_SESSION['login_user'];
			$table = mysqli_query($connection,$query);

			if($table){
				$rows=mysqli_num_rows($table);
				if($rows > 0){
				    for($x = 0; $x<= $row; $x++){
						$row = mysqli_fetch_assoc($table);
						if ($_GET['user'] == $row['contact_id']){
							?>
							$("button#add-contact").html("Added in Contact List");
							$("button#add-contact").val(1);
							$("button#add-contact").css({"background-color":"white","border":"1px solid #16b980", "color":"#16b980"});
							<?php
							$_SESSION['added'] = 1;
							break;
						}
					}
				}
			}
		}
	?>

	$("form#form-edu").hide();
	$("form#form-int").hide();
	$("form#form-ski").hide();
	$("button#cancel-edu").click(function(){
		$("form#form-edu").hide();
	});
	$("button#cancel-ski").click(function(){
		$("form#form-ski").hide();
	});
	$("button#cancel-int").click(function(){
		$("form#form-int").hide();
	});
	$("button#add-edu").click(function(){
		$("form#form-edu").toggle();
	});
	$("button#add-ski").click(function(){
		$("form#form-ski").toggle();
	});
	$("button#add-int").click(function(){
		$("form#form-int").toggle();
	});
});
</script>
</body>
</html>