<?
	/**
	 * This page deletes a quiz
	 * @todo Secure input
	 * @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	 * @todo Fix Access-Control-Allow-Origin issue
	 */

	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	header('Access-Control-Allow-Origin: *');

	//set the data type to JSON

	//check to see if $_REQUEST
	if (isset($_GET['data'])) {
		$file = 'quizzes/' . $_GET['data'] . '.json'//$file contains the name of our file
	} else {
		echo "Request empty";
		exit();
	}

	if (!$file) {
		echo "File not found!";
		exit();
	}
	unlink($file) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
	echo "Success";