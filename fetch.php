<?  
	/**
	* This page fetches a requested quiz
	* @todo Secure input
	* @todo Change to SQL instead of JSON when quiz creation is set up to SQL
	* @todo Fix cross-domain AJAX requests
	*/
	
	header('Access-Control-Allow-Origin: *');

	//set the data type to JSON
	header('Content-type: application/json');
		
	//check to see if $_REQUEST
	if (isset($_GET['data'])){
		$file = 'quizzes/'.$_GET['data'].'.json'//$file contains the name of our file
	} else {
		echo "Request empty";
		exit();
	}

	//read and return text from the JSON file
	if (file_get_contents($file, true) == true){
		echo file_get_contents($file, true);
	}
	else {
		echo "Cannot open file";
	}