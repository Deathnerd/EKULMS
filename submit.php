<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 7/24/14
	 * Time: 6:13 PM
	 */

	session_start();
	require_once("autoloader.php");
	//check if user is signed in and correct data is received
	if (!$Utils->checkIsSet(array($_SESSION['userName'],
	                              $_GET['payload'],
	                              $_GET['action']),
		array("User is not logged in. Your session may have expired. Please log in",
		      "Payload not received",
		      "Action not set"))
	) {
		if (!isset($_SESSION['userName'])) {
			session_destroy();
		}
		exit();
	}
	$Users = new Users($DB);
	$Courses = new Courses($DB);
	$Tests = new Tests($DB);

	$user_name = $_SESSION['userName'];
	//get all columns from user's row
	$user_info = $Users->fetchUser($user_name);
	$user_id = intval($user_info['id']);

	$enrolled_courses = $Courses->fetchEnrolledCourses($user_name, "student");
	$payload = json_decode($_GET['payload'], true);

	$test_name = $payload['_testName'];

	if ($Tests->testExists($test_name)) {
		$test_id = $Tests->getIdByName($test_name);
	} else {
		$Utils->exitWithMessage("I have no idea how this happened, but the test does not exist");
	}

//	$user_is_enrolled = $Utils->recursive_array_search($payload['_courseId'], $enrolled_courses);
	/*if (!$user_is_enrolled) {
		$Utils->exitWithMessage("User is not enrolled in this course. I have no idea how this happened");
	}*/

	//calculate the user's number of correct/wrong anwers and their score
	$number_of_correct_questions = 0;
	foreach ($payload['answers'] as $answer) {
		if ($answer['correct']) {
			$number_of_correct_questions++;
		}
	}
	$number_of_questions = count($payload['answers']);
	$number_of_incorrect_questions = $number_of_questions - $number_of_correct_questions;
	$test_id = $Tests->getIdByName($payload['_testName']);
	if ($Tests->submitResults($user_id, $test_id, $number_of_correct_questions, $number_of_incorrect_questions)) {
		$score = $number_of_correct_questions . '/' . ($number_of_correct_questions + $number_of_incorrect_questions);
		$Utils->exitWithMessage("Thank you for your submission. Your score is: $score");
	}

	$Utils->exitWithMessage("There was an error submitting your results. Please inform the administrator");
