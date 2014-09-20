<?
	/**
	 * This page deletes a quiz
	 * @todo Secure input
	 * @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	 * @todo Fix Access-Control-Allow-Origin issue
	 */

	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	require_once("autoloader.php");
	header('Access-Control-Allow-Origin: *');

	//set the data type to JSON

	//check to see if $_REQUEST
	if (!$Utils->checkIsSet(array($_GET['data']), array("Request empty"))) {
		exit();
	}
	$file = 'quizzes/' . $_GET['data'] . '.json'; //$file contains the name of our file

	if (!$file) {
		exit("File not found!");
	}
	unlink($file) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
	exit("Success");