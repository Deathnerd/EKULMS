<?
	/**
	 * This will recieve the JSON from the create page and post to a json file
	 * @todo Secure the input
	 * @todo Fix cross-domain AJAX requests
	 */

	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	header('Access-Control-Allow-Origin: *');
	//set the data type to text
	header('Content-type: application/text');

	//check to see if $_REQUEST
	if (isset($_GET['data'])) {
		$data = $_GET['data'];
	} else {
		echo "Request empty";
		exit();
	}
	//turn the data into an object
	$json = json_decode(stripslashes($data), true);
	echo $json;
	//get the name of the quiz
	$name = $json["_quizName"];
	// create a file with the name of the quiz
	$file = fopen('quizzes/' . $name . '.json', 'w') or die ('Cannot open file!');
	$content = json_encode($json);
	//write to the file the json data
	fwrite($file, $content) or die('Cannot write to file!');
	fclose($file);
	echo "Success!";