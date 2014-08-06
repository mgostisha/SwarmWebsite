<?php

//----- Define Server Parameters ------

 $SERVER = 'shock.astro.wisc.edu';
 $PORT = 11000;
 $TIMEOUT = 3;

//----- Open Connection to Server -----

 $stream = stream_socket_client("tcp://$SERVER:$PORT", $errno, $errstr, $TIMEOUT);

 if (!$stream) {
	$response = "There was a problem connecting to the Swarm server: ".htmlentities($errstr);
	echo $response;
 }

 else {

 	if(isset($_FILES['txtfile']['name']) && !empty($_FILES['txtfile']['name'])){

		//----- Rename file parameters for ease of use

 		$mime_type= $_FILES['txtfile']['type'];
 		$tmp_name = $_FILES['txtfile']['tmp_name'];
 		$name = $_FILES['txtfile']['name'];
 		$error = $_FILES['txtfile']['error'];
 		$uploads_dir = "/d/www/gostisha/uploads/";

 		//----- Check to make sure the file is a text file, is a valid file,
 		//----- and is moved to a new directory correctly -----

	 	if (!preg_match('/^text/', $mime_type)) {
 			$errors[] = "The uploaded file is not a text file.";
 		}
 		elseif (!is_uploaded_file($tmp_name)) {
 			$errors[] = "The file is not valid.";
 		}
 		elseif (!move_uploaded_file($tmp_name, $uploads_dir.$name)) {
 			$errors[] = "There was a problem moving the file.";
 		}

 		//if (!$errors) {
 		//	echo "The file was uploaded, validated, and moved correctly! Click <a href='http://www.astro.wisc.edu/~gostisha/swarm.html'>
 		//	here</a> to return to the Swarm homepage.";
 		//}

 		//----- Write file name and other parameters to the stream -----

	 	fputs($stream, "filename ".$uploads_dir.$name."\n");

	 }

 	foreach ($_POST as $key => $value) {
 		fputs($stream, "$key $value\n");
 	}

 	//if(empty($_FILES['txtfile']['name'])){
	// 	echo "The parameters have been sent. Click <a href='http://www.astro.wisc.edu/~gostisha/swarm.html'>
	// 	here</a> to return to the Swarm homepage";
	// }

	if (!$errors) {
	 	header('Location: swarm.success.php');
	}
	else {
		header('Location: swarm.failed.php?errMsg='.urlencode($errors));
	}

  }

?>