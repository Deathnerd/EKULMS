<?
	/**
	 * This script handles the logic to make a course
	 */
	require_once('../requires/Globals.php');

	//need an action
	if (!isset($_GET['action'])) {
		echo "Action not set!";
		exit();
	}
	$action = $_GET['action'];

	switch ($action) {
		//if the action is to create a course
		case "createCourse":
		{
			//create course requires $courseName, $courseId, and $description
			if (!isset($_GET['courseName'])) {
				echo "Course name required!";
				break;
			} elseif (!isset($_GET['courseId'])) {
				echo "Course id required!";
				break;
			} elseif (!isset($_GET['userName'])) {
				echo "Username required!";
				break;
			}
			if (isset($_GET['description'])) {
				$description = $_GET['description'];
			} else {
				$description = " ";
			}
			$courseId = $_GET['courseId'];
			$courseName = $_GET['courseName'];
			$userName = $_GET['userName'];
			//if the course does not exist
			if ($Courses->fetchById($courseId) == false) {
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
			if (is_array($list)) {
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
			if (!isset($_GET['courseId'])) {
				echo "Course id required!";
				exit();
			} elseif (!isset($_GET['userName'])) {
				echo "Username required!";
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
			if (!isset($_GET['courseId'])) {
				echo "Course id required!";
				exit();
			} elseif (!isset($_GET['userName'])) {
				echo "Username required!";
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