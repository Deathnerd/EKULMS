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
		echo "Request empty";
		exit();
	}

	$json = json_decode(stripslashes($data), true);

	require_once('requires/Tests.php');
	$Tests = new Tests();
	if($Tests->makeTest($json)){
		echo "Success!";
	} else {
		echo "failed";
	}

	exit();