<?
	/**
	 * This page fetches a requested quiz
	 */

	require_once("autoloader.php");
	$Tests = new Tests($DB);

	if (!$Utils->checkIsSet(array($_GET['data']), array("Request empty!"))) {
		exit();
	}

	$results = $Tests->fetchByName($Tests->getNameById(intval($_GET['data'])));
	if (!$results) {
		$Utils->exitWithMessage("Failed!");
	}

	exit(json_encode($results));