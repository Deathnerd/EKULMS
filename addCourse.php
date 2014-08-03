<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 6/29/14
	 * Time: 4:26 PM
	 */
	require_once('requires/Globals.php');
	session_start();
	
	if (!$_SESSION['userName'] || $_SESSION['admin'] != '1') { //if a user is not already signed in
		exit();
	}

	if (!isset($_GET['courseId']) || !isset($_GET['courseName'])) {
		echo "Check values";
		exit();
	}

	if($Courses->courseExists($_GET['courseId'])){
		echo "Course already exists";
		$DB->close();
		exit();
	}

	if(!$Courses->addCourse($_GET['courseId'], $_GET['courseName'], $_GET['description'])){
		echo "Failed to add course to the database. Contact administrator";
	} else {
		echo("Success");
	}
	$DB->close();
