<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 7/24/14
	 * Time: 6:13 PM
	 */

	session_start();
	require_once("autoloader.php");
	$Utilities = new Utilities();
	//check if user is signed in and correct data is received
	if (!$Utilities->checkIsSet(array($_SESSION['userName'],
	                                  $_GET['payload'],
	                                  $_GET['action']),
	                            array("User is not logged in. Your session may have expired. Please log in",
	                                  "Payload not received",
	                                  "Action not set"))
	) {
		exit();
	}
	$DB = new Db();
	$Users = new Users($DB);
	$Courses = new Courses($DB);
	$Tests = new Tests($DB);

	$user_name = $_SESSION['userName'];
	//get all columns from user's row
	$user_info = $Users->fetchUser($user_name);
	$user_id = $user_info['id'];

	$enrolled_courses = $Courses->fetchEnrolledCourses($user_name, "student");
	$enrolled_course_id = $enrolled_courses['courseId'];
	$payload = json_decode($_GET['payload']);

	if ($Tests->testExists($payload['_testName'])) {
		$test_id = $Tests->fetchByName($payload['_testName']);
		$test_id = $test_id['testId'];
	} else {
		$Utilities->closeAndExit($DB, "I have no idea how this happened, but the test does not exist");
	}

	if ($enrolled_course_id != $payload['_courseId']) {
		$Utilities->closeAndExit($DB, "User is not enrolled in this course. I have no idea how this happened");
	}

	//calculate the user's number of correct/wrong anwers and their score
	$number_of_correct_questions = 0;
	foreach ($payload['answers'] as $answer) {
		if ($answer['correct']) {
			$number_of_correct_questions++;
		}
	}
	$number_of_questions = count($payload['answers']);
	$number_of_incorrect_questions = $number_of_questions - $number_of_correct_questions;

	if($Tests->submitResults($user_id, $test_id, $number_of_correct_questions, $number_of_incorrect_questions)){
		$Utils->closeAndExit($DB, "Thank you for your submission");
	}

	$Utils->closeAndExit($DB, "There was an error submitting your results. Please inform the administrator");
