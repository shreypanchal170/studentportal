<?php
    include('class.fileuploader.php');
	
	$customName = isset($_POST['custom_name']);
	
	// initialize FileUploader
    $FileUploader = new FileUploader('files', array(
        'uploadDir' => '../docs/',
        'title' => $customName ? $customName : 'name'
    ));
	
	// call to upload the files
    $data = $FileUploader->upload();

	// export to js
	echo json_encode($data);
	exit;