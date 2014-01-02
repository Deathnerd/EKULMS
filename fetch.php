<?php  
	//fetch.php
	//This will fetch the requested JSON file from an AJAX and return said JSON to the client
	
	//TODO
	//Secure the input

	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	header('Access-Control-Allow-Origin: *');

	//set the data type to JSON
	header('Content-type: application/json');
		
	//check to see if $_REQUEST
	if (isset($_GET['request'])){
		$request = $_GET['request']; //$request contains the name of our file
	} else {
		echo "request empty";
		exit();
	}

	//read and return text from the JSON file
	if (file_get_contents($request, true) == true){
		echo file_get_contents($request, true);
	}
	else {
		die("Cannot open file");
	}
?>