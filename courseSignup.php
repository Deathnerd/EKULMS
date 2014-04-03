<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/2/14
	 * Time: 6:37 PM
	 */

	session_start();
	if (!$_SESSION['userName']) { //if a user is already signed in
		echo("userName not set");
		exit();
	}

	if (!isset($_GET['courseId'])) {
		echo "courseId not set!";
		exit();
	}

	if (!is_file('requires/Users.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Users.php! Check your installation");
	}
	require_once('requires/Courses.php'); //import the user database functions

	if (!is_file('requires/Courses.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Courses.php! Check your installation");
	}
	require_once('requires/Courses.php'); //import the course database functions

	//print the header
	require('requires/header.php');

	$Courses = new Courses;

	if (!$Courses->courseExists($_GET['courseId'])) {
		echo("Course does not exist");
		die("Course does not exist");
	}
	//insert user into course enrollment table
	if (!$Courses->addStudent($_SESSION['courseId'], $_SESSION['userName'])) {
		echo("Failed to add course");
		exit();
	}
	echo("Success");
	$Courses->close(); //close connection
	exit();