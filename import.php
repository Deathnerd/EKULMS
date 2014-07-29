<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/16/14
	 * Time: 7:09 PM
	 */

	if(!is_file('requires/Tests.php')){
		echo "Test.php not found. Check your installation.";
		exit("Required file not found");
	}

	require_once('requires/Tests.php');

	$Test = new Tests();
	chdir("./quizzes");
	$files = scandir(".");
	for($i = 2; $i < count($files); $i++){
		print_r($files[$i]);
		$f = file_get_contents($files[$i]);
		$json = json_decode(stripslashes($f), true);

		if($Test->makeTest($json)){
			echo "   Success!<br /><br />";
		} else {
			echo "   Fail! <br /><br />";
		}
	}
	exit();
