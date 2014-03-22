<?
	/**
	* This script handles the logic to make a course
	*/

	//if the user isn't an admin, send them to the signin page
	if(!isset($_SESSION['admin']) && $_SESSION['admin'] != '1') {
		header('Location: signin.php');
	}
	
	if(!isset($_GET['courseName'])){
		echo "Course Name required";
		exit();
	} else if(!isset($_GET['courseId'])){
		echo "Course Id required";
		exit();
	}
	$courseId = $_GET['courseId'];
	$courseName = $_GET['courseName'];
	$description = $_GET['description'];

	//check requirements
	if(!is_file('requires/Users.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Users.php! Check your installation");
	}
	require_once('requires/Users.php')//import the user database methods
	if(!is_file('requires/Courses.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Courses.php! Check your installation");
	}
	require_once('requires/Courses.php')//import the user database methods
	$User = new Users;
	$Courses = new Courses;

	//if the course does not exist
	if($Courses->fetchById($courseId) == false){
		//create it
		if(!$Courses->create($courseName, $courseId, $description)){
			echo "Ruh-roh, Raggy! Something went wrong!";
			$User->parent::close();
			exit();
		} else {
			echo "Success fully created course";
			$User->parent::close();
			exit();
		}
	}