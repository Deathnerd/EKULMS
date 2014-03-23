<?
	/**
	* This script handles the logic to make a course
	*/

	//need an action
	if(!isset($_GET['action'])){
		echo "Action not set!";
		exit();
	if($_GET['action'] != "list"){ //if not listing courses
		} else if(!isset($_GET['courseName'])){
			echo "Course Name required";
			exit();
		} else if(!isset($_GET['courseId'])){
			echo "Course Id required";
			exit();
		}
		$courseId = $_GET['courseId'];
		$courseName = $_GET['courseName'];
		$description = $_GET['description'];
	}
	$action = $_GET['action'];

	if(!is_file('../requires/Courses.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Courses.php! Check your installation");
	}
	require_once('../requires/Courses.php');//import the user database methods
	// require_once('../requires/Db.php');
	$Courses = new Courses;

	//if the action is to create a course
	if($action == "createCourse"){
		//if the course does not exist
		if($Courses->fetchById($courseId) == false){
			//create it
			if(!$Courses->create($courseName, $courseId, $description)){
				echo "Ruh-roh, Raggy! Something went wrong!";
				// $Db->close();
				exit();
			} else {
				echo "Success`fully created course";
				// $Db->close();
				exit();
			}
		} else {
			echo "Course already exists!";
			exit();
		}
		exit(); //safety exit
	} else if($action == "list") {
		$list = $Courses->fetchAll();
		if(is_array($list)){
			header('Access-Control-Allow-Origin: *');
			header("Content-type: application/json");
			$json = json_encode($list, JSON_PRETTY_PRINT);
			echo var_dump($json);
			exit();
		} else{
			echo "Nothing there!";
		}
	}