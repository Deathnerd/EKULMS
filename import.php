<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/16/14
	 * Time: 7:09 PM
	 */

	require_once("autoloader.php");

	chdir("./quizzes");

	$DB = new Db();
	$Tests = new Tests($DB);
	$files = scandir(".");
	for($i = 2; $i < count($files); $i++){
		print_r($files[$i]);
		$f = file_get_contents($files[$i]);
		$json = json_decode(stripslashes($f), true);

		if($Tests->makeTest($json)){
			echo "Success!<br /><br />";
		} else {
			echo "Fail!<br /><br />";
		}
	}
	$DB->close();
	exit();