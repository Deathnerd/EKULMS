<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 9/2/14
	 * Time: 2:30 AM
	 */

	require_once('autoloader.php');

	$Utils = new Utilities();

	if (!$Utils->checkIsSet(array($_GET['courseId']),
	                        array("No course id received"))
	) {
		exit();
	}
	$DB = new Db();
	$Tests = new Tests($DB);
	$Courses = new Courses($DB);

	$username = "admin";
	$courseId = $_GET['courseId'];

	$json = $Utils->jsonObject('tests');
	$courses = $Courses->fetchEnrolledCourses($username, 'student');
	foreach ($courses as $course) {
		$j = 0;
		$tests = $Tests->fetchAllByCourseId($_GET['courseId']);
		foreach ($tests as $test) {
			$json['tests'][$j]["testName"] = $test['testName'];
			$json['tests'][$j]["testId"] = $test['testId'];
			$json['tests'][$j]["testNumber"] = $test['testNumber'];
			$json['tests'][$j]["courseId"] = $test['courseId'];
			$j++;
		}
	}
	unset($tests);
	unset($j);

	$Utils->print_pre(json_encode($json));
	$Utils->closeAndExit($DB);