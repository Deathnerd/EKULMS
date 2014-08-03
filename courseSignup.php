<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/2/14
	 * Time: 6:37 PM
	 */

	require_once('requires/Globals.php');
	session_start();

	if (!$_SESSION['userName']) { //if a user is already signed in
		echo "userName not set";
		exit();
	}

	if (!isset($_GET['courseId'])) {
		echo "courseId not set!";
		exit();
	}

	if (!$Courses->courseExists($_GET['courseId'])) {
		echo "Course does not exist";
		exit();
	}
	//insert user into course enrollment table
	if (!$Courses->addStudent($_GET['courseId'], $_SESSION['userName'])) {
		echo "Failed to sign up for course. Contact administrator";
		exit();
	}
	echo("Success");
	$DB->close(); //close connection
	exit();