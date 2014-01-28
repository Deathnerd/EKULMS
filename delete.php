<?
	//Delete.php
	//This will delete a specified file
	
	//TODO
	//Secure the input

	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	header('Access-Control-Allow-Origin: *');

	//set the data type to JSON
		
	//check to see if $_REQUEST
	if (isset($_GET['data'])){
		$file = 'quizzes/'.$_GET['data'].'.json'; //$file contains the name of our file
	} else {
		echo "Request empty";
		exit();
	}

	if(!$file){
		echo "File not found!";
		exit();
	}
	unlink($file) or die("Some stuff happened here!");
	echo "Success";
?>