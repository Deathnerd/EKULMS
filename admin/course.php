<?
	/**
	 * This script handles the logic to make a course
	 */
	require_once('../autoloader.php');
	$Utils = new Utilities($DB);
	$Courses = new Courses($DB);
	//need an action
	$action = $Utils->checkIsSet(array($_GET['action']), array("Action not set!")) ? $_GET['action'] : exit();

	switch ($action) {
		//if the action is to create a course
		case "createCourse":
		{
			//create course requires $courseName, $courseId, and $description

			if (!$Utils->checkIsSet(array($_GET['courseName'], $_GET['courseId'], $_GET['userName']),
			                       array("Course name required!", "Course id required!", "Username required!"))
			) {
				break;
			}
			$description = isset($_GET['description']) ? $_GET['description'] : " ";

			$courseId = $_GET['courseId'];
			$courseName = $_GET['courseName'];
			$userName = $_GET['userName'];
			//if the course does not exist
			if (!$Courses->fetchById($courseId)) {
				//create it
				if (!$Courses->create($courseName, $courseId, $description)) {
					echo "Failed to create course";
				} else {
					echo "Successfully created course";
				}
			} else {
				echo "Course already exists!";
			}
			break;
		}
		//if the action is to list the courses
		case "list":
		{
			$list = $Courses->fetchAll();
			if ($list) {
				header('Access-Control-Allow-Origin: *');
				header("Content-type: application/json");
				echo json_encode($list);
				break;
			} else {
				echo "Nothing there!";
				break;
			}
		}
		//if the action is to add a student
		case "addStudent":
		{
			//requires $courseId an and $userName
			if (!$Utils->checkIsSet(array($_GET['courseId'], $_GET['userName']), array("Course id required!", "Username required!"))) {
				break;
			}
			$userName = $_GET['userName'];
			$courseId = $_GET['courseId'];
			//if the user was successfully added
			if ($Courses->addStudent($courseId, $userName)) {
				echo "$userName successfully added to $courseId";
			} else {
				echo "Error adding student";
			}
			break;
		}
		//if the action is to add a student
		case "addInstructor":
		{
			//requires $courseId and $userName
			if (!$Utils->checkIsSet(array($_GET['courseId'], $_GET['userName']), array("Course id required!", "Username required!"))) {
				exit();
			}
			$userName = $_GET['userName'];
			$courseId = $_GET['courseId'];
			//if the user was successfully added
			if ($Courses->addInstructor($courseId, $userName)) {
				echo "$userName successfully added to $courseId";
			} else {
				echo "Error adding instructor";
			}
			break;
		}
		default:
			{
			echo "$action is not a valid action";
			}
	}
	exit();