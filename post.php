<?
	/**
	 * This will recieve the JSON from the create page and post to a json file
	 * @todo Secure the input
	 * @todo Fix cross-domain AJAX requests
	 */

	require_once('requires/Globals.php');
	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	header('Access-Control-Allow-Origin: *');
	//set the data type to text
//	header('Content-type: application/text');

	//check to see if $_REQUEST
	if (isset($_GET['data'])) {
		$data = $_GET['data'];
	} else {
		$DB->close();
		exit("Request empty!");
	}

	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	} else {
		$DB->close();
		exit("Action empty!");
	}

	$json = json_decode(stripslashes($data), true);

	switch ($action) {
		case "update":
		{
			if($Tests->testExists($json['_quizName']) && $Tests->updateTest($json)){
				$DB->close();
				exit("Success!");
			} else {
				$DB->close();
				exit("Failed to update test");
			}
			break;
		}
		case "make":
		{
			if(!$Tests->testExists($json['_quizName']) && $Tests->makeTest($json)){
				$DB->close();
				exit("Success!");
			} else {
				$DB->close();
				exit("Failed to make test");
			}
			break;
		}
		default:{
			$DB->close();
			exit("Invalid action!");
		}
	}