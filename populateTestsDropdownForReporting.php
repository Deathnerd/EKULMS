<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/28/2014
	 * Time: 9:46 PM
	 */

	require_once('autoloader.php');
	session_start();
	$Courses = new Courses($DB);
	$Users = new Users($DB);
	$Tests = new Tests($DB);

	$check_array = array($_GET['courseId']);
	$error_array = array("CourseId not set!");

	if(!$Utils->checkIsSet($check_array, $error_array)){
	    exit();
	}

	$tests = $Tests->fetchAllByCourseId($_GET['courseId']);
	if(!$tests){
		$Utils->exitWithMessage("Error getting tests");
	}

	exit(json_encode($tests));
