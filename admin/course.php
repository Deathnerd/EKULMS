<?
	/**
	* This script handles the logic to make a course
	* @todo Add database disconnect
	*/

	//need an action
	if(!isset($_GET['action'])){
		echo "Action not set!";
		exit();
	}
	$action = $_GET['action'];

	if(!is_file('../requires/Courses.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Courses.php! Check your installation");
	}
	require_once('../requires/Courses.php');//import the user database methods
	$Courses = new Courses;

	switch ($action){
		//if the action is to create a course
		case "createCourse": {
			//create course requires $courseName, $courseId, and $description
			if(!isset($_GET['courseName'])){
				echo "Course name required!";
				exit();
			} else if(!isset($_GET['courseId'])){
				echo "Course id required!";
				exit();
			} else if(!isset($_GET['userName'])){
				echo "Username required!";
				exit();
			}
			//if the course does not exist
			if($Courses->fetchById($courseId) == false){
				//create it
				if(!$Courses->create($courseName, $courseId, $description)){
					echo "Ruh-roh, Raggy!";
				} else {
					echo "Successfully created course";
				}
			} else {
				echo "Course already exists!";
				exit();
			}
			break;
		}
		//if the action is to list the courses
		case "list": {
			$list = $Courses->fetchAll();
			if(is_array($list)){
				header('Access-Control-Allow-Origin: *');
				header("Content-type: application/json");
				echo json_encode($list);
				exit();
			} else{
				echo "Nothing there!";
			}
		} 
		//if the action is to add a student
		case "addStudent": {
			//requires $courseId an and $userName
			if(!isset($_GET['courseId'])){
				echo "Course id required!";
				exit();
			} else if(!isset($_GET['userName'])){
				echo "Username required!";
				exit();
			}
			//if the user was successfully added
			if($Courses->addStudent($courseId, $userName)){
				echo $userName." succefully added to ".$courseId;
			} else {
				echo "Error adding student";
			}
			break;
		}
		//if the action is to add a student
		case "addInstructor": {
			//requires $courseId and $userName
			if(!isset($_GET['courseId'])){
				echo "Course id required!";
				exit();
			} else if(!isset($_GET['userName'])){
				echo "Username required!";
				exit();
			}
			//if the user was successfully added
			if($Courses->addInstructor($courseId, $userName)){
				echo $userName." succefully added to ".$courseId;
			} else {
				echo "Error adding instructor";
			}
			break;
		}
		default: {
			echo "No action sent";
			die("Error in ".__FILE__." on line ".__LINE__.". ".$action." is not a valid action");
		}
	}
	exit();