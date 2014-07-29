<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/7/14
	 * Time: 8:02 PM
	 */
	error_reporting(E_ALL);
	require_once('utils/utilities.php');
	$Utils = new Utilities();
	session_start();

	$Utils->checkFile('requires/Courses.php', __FILE__, __LINE__);

	require_once('requires/Courses.php');

	$Courses = new Courses;

	$enrolledCourses = $Courses->fetchEnrolledCourses($_SESSION['userName']);

	//if the user is not enrolled in any courses
	if (is_array($enrolledCourses)) {
		header('Access-Control-Allow-Origin: ');
		header('Content-type: application/json');
		echo json_encode($enrolledCourses);
	} else {
		echo "UH OH!";
	}
	$Courses->close();
	exit();