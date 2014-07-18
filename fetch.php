<?
	/**
	 * This page fetches a requested quiz
	 * @todo Secure input
	 * @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	 * @todo Fix cross-domain AJAX requests
	 */

	header('Access-Control-Allow-Origin: *');

	require_once('requires/Tests.php');
	$Test = new Tests();

	if(!$results = $Test->fetchByName($_GET['data'])){
		exit(json_encode(array('_quizName' => "Failed To Load")));
	}

	exit(json_encode($results));
