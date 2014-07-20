<?
	/**
	 * This page fetches a requested quiz
	 * @todo Secure input
	 * @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	 * @todo Fix cross-domain AJAX requests
	 */

	header('Access-Control-Allow-Origin: *');
	header("Content-type: application/text");
	if(!isset($_GET['data'])){
		exit("Request Empty!");
	}

	require_once('requires/Tests.php');
	$Test = new Tests();
	$results = $Test->fetchByName($_GET['data']);
	if(!$results){
		echo "Failed!";
		exit();
	}

	$json = json_encode($results);
//	header("Content-type: application/text");
	echo $json;
