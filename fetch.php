<?
	/**
	 * This page fetches a requested quiz
	 * @todo Secure input
	 * @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	 * @todo Fix cross-domain AJAX requests
	 */

	header('Access-Control-Allow-Origin: *');

	//set the data type to JSON
	header('Content-type: application/json');

	//check to see if $_REQUEST
	if (isset($_GET['data'])) {
		$file = 'quizzes/' . $_GET['data'] . '.json'; //$file contains the name of our file
	} else {
		echo "Request empty";
		exit();
	}

	$contents = file_get_contents($file, true);
	var_dump($stuff);
	//read and return text from the JSON file
	if ($contents == true) {
		//comment for debugging
		echo $contents;
		//uncomment for debugging
//		$stuff = json_decode($contents, true);
//		for ($i = 1; $i < count($stuff['quiz']['questions'][0]['choices']); $i++){
//			echo $stuff['quiz']['questions'][0]['choices'][$i]['value']."\n";
//		}
	} else {
		echo "Cannot open file";
	}