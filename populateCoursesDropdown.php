<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/16/14
	 * Time: 1:07 AM
	 */

	require_once("autoloader.php");

	if (!$Utils->checkIsSet(array($_GET['courseId']), array("Course Id not set!"))) {
		exit();
	}

	if ($Courses->courseExists($_GET['courseId'])) {
		$testsInCourse = $Tests->fetchAllByCourseId($_GET['courseId']);
		$json = json_encode($testsInCourse);
		exit($json);
	}
	exit(json_encode("FAIL"));
