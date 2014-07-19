<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 6/29/14
	 * Time: 4:26 PM
	 */
	error_reporting(E_ALL);
	require_once('utils/utilities.php');
	$Utils = new Utilities();
	session_start();
	
	if (!$_SESSION['userName'] || $_SESSION['admin'] != '1') { //if a user is not already signed in
		exit();
	}

	if (!isset($_GET['courseId']) || !isset($_GET['courseName'])) {
		echo "Check values";
		exit();
	}

	if(!is_file('requires/Courses.php')){
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Courses.php! Check your installation");
	}
	require_once('requires/Courses.php');

	$Courses = new Courses();

	if($Courses->courseExists($_GET['courseId'])){
		echo "Course already exists";
		exit();
	}

	if(!$Courses->addCourse($_GET['courseId'], $_GET['courseName'], $_GET['description'])){
		echo "Failed to add course to the database. Contact administrator";
	} else {
		echo("Success");
	}
	$Courses->close();
