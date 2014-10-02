<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/28/2014
	 * Time: 10:55 PM
	 */

	require_once('autoloader.php');
	session_start();
	if (!$Utils->checkIsSet(array($_GET['testName']), array("Test name not sent"))) {
		exit();
	}

	$Tests = new Tests($DB);

	$results = $Tests->getResults((int)$_SESSION['id'], (int)$Tests->getIdByName($_GET['testName']), 'submitted ASC');
	if (!$results) {
		$Utils->exitWithMessage("No results found");
	}
	$json = json_encode($results);
	exit($json);