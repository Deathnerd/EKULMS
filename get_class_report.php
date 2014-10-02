<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/28/2014
	 * Time: 10:54 PM
	 */

	require_once('autoloader.php');
	session_start();

	if (!$Utils->checkIsSet(array($_GET['courseId'], $_GET['courseName']),
		array("Course id not set", "Course name not set"))
	) {
		exit();
	}

	$Courses = new Courses($DB);
	$Tests = new Tests($DB);
	$Users = new Users($DB);