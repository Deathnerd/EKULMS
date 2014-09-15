<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/2/14
	 * Time: 6:37 PM
	 */

	require_once("autoloader.php");
	session_start();
	$DB = new Db();
	$Courses = new Courses($DB);
	$Utilities = new Utilities();
	$User = new Users($DB);

	if (!$Utilities->checkIsSet(array($_SESSION['userName'], $_GET['courseId']), array("User name not set!", "CourseId not set!"))) {
		exit();
	}

	if (!$Courses->courseExists($_GET['courseId'])) {
		$Utilities->closeAndExit($DB, "Course does not exist");
	}
	//insert user into course enrollment table
	if ($User->isEnrolled($_GET['courseId'], $_SESSION['userName'])) {
		$Utilities->closeAndExit($DB, "User is already enrolled in the course");
	}
	if (!$Courses->addStudent($_GET['courseId'], $_SESSION['userName'])) {
		$Utilities->closeAndExit($DB, "Failed to sign up for course. Contact administrator");
	}
	$Utilities->closeAndExit($DB, "Success");