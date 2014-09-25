<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/16/14
	 * Time: 1:07 AM
	 */

	require_once("autoloader.php");

	$_GET['courseId'] = "CSC185";
	if (!$Utils->checkIsSet(array($_GET['courseId']), array("Course Id not set!"))) {
		exit();
	}

	$Tests = new Tests($DB);
	$Users = new Users($DB);
	$Courses = new Courses($DB);

	if ($Courses->courseExists($_GET['courseId'])) {
		$testsInCourse = $Tests->fetchAllByCourseId($_GET['courseId']);
		$json = json_encode($testsInCourse);
		exit($json);
	}
	exit(json_encode("FAIL"));
