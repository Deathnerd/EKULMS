<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 6/29/14
	 * Time: 4:26 PM
	 */
	require_once("autoloader.php");
	session_start();

	$Utils = new Utilities();
	$DB = new Db();
	$Courses = new Courses($DB);

	if (!$Utils->checkIsSet(array($_SESSION['userName'], $_GET['courseId'], $_GET['courseName']),
	                        array("", "CourseId not set", "Course Name not set"))
		|| $_SESSION['admin'] != '1'
	) { //if a user is not already signed in or is not an admin
		exit();
	}

	if ($Courses->courseExists($_GET['courseId'])) {
		$Utils->closeAndExit($DB, "Course already exists");
	}

	if (!$Courses->addCourse($_GET['courseId'], $_GET['courseName'], $_GET['description'])) {
		$Utils->closeAndExit($DB, "Failed to add course to the database. Contact administrator");
	}

	$Utils->closeAndExit($DB, "Success");