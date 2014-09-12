<?
	/**
	 * This page fetches a requested quiz
	 * @todo Secure input
	 * @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	 * @todo Fix cross-domain AJAX requests
	 */

	require_once("autoloader.php");
	header('Access-Control-Allow-Origin: *');
	header("Content-type: application/text");
	$DB = new Db();
	$Tests = new Tests($DB);
	$Utils = new Utilities();

	if (!$Utils->checkIsSet(array($_GET['data']), array("Request empty!"))) {
		exit();
	}

	$results = $Tests->fetchByName($_GET['data']);
	if (!$results) {
		exit("Failed!");
	}

	$DB->close();
	exit(json_encode($results));
