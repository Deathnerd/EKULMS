<?
	/**
	 * This will recieve the JSON from the create page and post to a json file
	 * @todo Secure the input
	 * @todo Fix cross-domain AJAX requests
	 */

	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	header('Access-Control-Allow-Origin: *');
	//set the data type to text
//	header('Content-type: application/text');

	//check to see if $_REQUEST
	if (isset($_GET['data'])) {
		$data = $_GET['data'];
	} else {
		exit("Request empty!");
	}

	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	} else {
		exit("Action empty!");
	}

	require_once('requires/Tests.php');
	$Tests = new Tests();

	$json = json_decode(stripslashes($data), true);

	switch ($action) {
		case "update":
		{

			break;
		}
		case "make":
		{
			break;
		}
		default:{
			exit("Invalid action!");
		}
	}

	if ($Tests->testExists($json['_quizName'])) {
		if ($Tests->updateTest($json)) {
			exit("Success");
		}
	} elseif ($Tests->makeTest($json)) {
		exit("Success");
	}

	exit("Failed");