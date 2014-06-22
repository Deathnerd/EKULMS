<?
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 4/7/14
 * Time: 8:02 PM
 */
	session_start();
	if (!is_file('requires/Courses.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Courses.php! Check your installation");
	}

	require_once('requires/Courses.php');

	$Courses = new Courses;

	$enrolledCourses = $Courses->fetchEnrolledCourses($_SESSION['userName']);

	//if the user is not enrolled in any courses
	if(is_array($enrolledCourses)){
		header('Access-Control-Allow-Origin: ');
		header('Content-type: application/json');
		echo json_encode($enrolledCourses);
	} else { echo "UH OH!";}
	$Courses->close();
	exit();