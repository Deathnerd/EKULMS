<?php
	//data.php
	//This will recieve the JSON from the create page and post to a json file
	
	//TODO
	//Secure the input

	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	header('Access-Control-Allow-Origin: *');

	//set the data type to text
	header('Content-type: application/text');
		
	//check to see if $_REQUEST
	if (isset($_GET['data'])){
		$data = $_GET['data'];
	} else {
		echo "Request empty";
		exit();
	}

	//turn the data into an object
	$json = json_decode($data, true);

	//get the name of the quiz
	$name = $json["_quizName"].'.json';

	//create a file with the name of the quiz
	fopen($name, 'w') or die ('Cannot open file!');

	//write to the file the json data
	fwrite($name, json_encode($json, JSON_PRETTY_PRINT)) or die('Cannot write to file!');

?>