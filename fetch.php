<?
	/**
	 * This page fetches a requested quiz
	 * @todo Secure input
	 * @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	 * @todo Fix cross-domain AJAX requests
	 */

	require_once('requires/Globals.php');
	header('Access-Control-Allow-Origin: *');
<<<<<<< HEAD

	require_once('requires/Tests.php');
	$Test = new Tests();

	if(!$results = $Test->fetchByName($_GET['data'])){
		exit(json_encode(array('_quizName' => "Failed To Load")));
	}

	exit(json_encode($results));
=======
	header("Content-type: application/text");
	if(!isset($_GET['data'])){
		exit("Request Empty!");
	}

	$results = $Tests->fetchByName($_GET['data']);
	if(!$results){
		echo "Failed!";
		exit();
	}

	$DB->close();
	exit(json_encode($results));
>>>>>>> Tests
