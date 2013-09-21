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
		$request = $_GET['request'];
	} else {
		echo "request empty";
		exit();
	}

	//read and return text from the JSON file
	echo file_get_contents("quiz_JSON/"+$request, true);
?>