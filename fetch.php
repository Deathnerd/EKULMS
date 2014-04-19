<?
	/**
	 * This page fetches a requested quiz
	 * @todo Secure input
	 * @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	 * @todo Fix cross-domain AJAX requests
	 */

	header('Access-Control-Allow-Origin: *');
	//set the data type to JSON
//	header('Content-type: application/text');
	//check to see if $_GET
	if (isset($_GET['data'])) {
//		$file = 'quizzes/' . $_GET['data'] . '.json'; //$file contains the name of our file
	} else {
		echo "Request empty";
		exit();
	}

//	$contents = file_get_contents($file, true);
//	//read and return text from the JSON file
//	if ($contents == true) {
//		//comment for debugging
//		echo $contents;
//	} else {
//		echo "Cannot open file";
//	}

	require_once('requires/Tests.php');
	$Test = new Tests();
	$results = $Test->fetchByName("Quiz 1");
	if(!$results){
		echo "Failed!";
		exit();
	}

	$json = json_encode($results);
//	header("Content-type: application/text");
	echo $json;
