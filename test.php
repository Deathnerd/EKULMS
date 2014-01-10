<?
	header('Access-Control-Allow-Origin: *');

	//set the data type to JSON
	header('Content-type: application/text');
	//check to see if $_REQUEST
	if (isset($_GET['data'])){
		$request = $_GET['data']; //$request contains the name of our file
		echo $request;
	} else {
		echo "Request empty";
		exit();
	}


?>