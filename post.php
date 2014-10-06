<?
	/**
	 * This will recieve the JSON from the create page and post to a json file
	 * @todo Secure the input
	 * @todo Fix cross-domain AJAX requests
	 */

	require_once("autoloader.php");
	//Allow cross-domain AJAX *UNSAFE. FIND ANOTHER WAY*
	header('Access-Control-Allow-Origin: *');

	//check to see if $_REQUEST
	if (!$Utils->checkIsSet(array($_GET['data'], $_GET['action']), array("Request Empty!", "Action Empty!"))) {
		exit();
	}

	$data = $_GET['data'];
	$action = $_GET['action'];

	$json = json_decode(stripslashes($data), true);

	switch ($action) {
		case "update": {
			if ($Tests->testExists($json['_quizName']) && $Tests->updateTest($json)) {
				$Utils->exitWithMessage("Success!");
			} else {
				$Utils->exitWithMessage("Failed to update test");
			}
			break;
		}
		case "make": {
			if (!$Tests->testExists($json['_quizName']) && $Tests->makeTest($json)) {
				$Utils->exitWithMessage("Success!");
			} else {
				$Utils->exitWithMessage("Failed to make test");
			}
			break;
		}
		default: {
		$Utils->exitWithMessage("Invalid action!");
		}
	}