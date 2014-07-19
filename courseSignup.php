<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/2/14
	 * Time: 6:37 PM
	 */

	error_reporting(E_ALL);
	require_once('utils/utilities.php');
	$Utils = new Utilities();
	session_start();

	if (!$_SESSION['userName']) { //if a user is already signed in
		echo "userName not set";
		exit();
	}

	if (!isset($_GET['courseId'])) {
		echo "courseId not set!";
		exit();
	}

	$Utils->checkFile('requires/Courses.php', __FILE__, __LINE__);
	require_once('requires/Courses.php'); //import the course database functions

	$Courses = new Courses;
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
	$Courses->close(); //close connection
	exit();