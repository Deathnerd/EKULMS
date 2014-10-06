<?
	/**
	 * This page fetches a requested quiz
	 */

	require_once("autoloader.php");

	if (!$Utils->checkIsSet(array($_GET['data']), array("Request empty!"))) {
		exit();
	}

	$results = $Tests->fetchByName($_GET['data']);
	if (!$results) {
		$Utils->exitWithMessage("Failed!");
	}

	exit(json_encode($results));