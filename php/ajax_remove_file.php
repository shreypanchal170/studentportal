<?php
if (isset($_POST['file'])) {
    $file = '../docs/' . $_POST['file'];
	
    if(file_exists($file))
		unlink($file);
}