<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/17/14
	 * Time: 12:32 AM
	 */

	require('autoloader.php');
	session_start();
	if (!$Utils->checkisSet(array($_SESSION['userName'],
	                              $_GET['action'],
	                              $_GET['test_name']),
	                        array("You are not logged in. Please do so",
	                              "Action was not set",
	                              "Test Name was not set"))
	) {
		exit();
	}

	if (isset($_GET['order'])) {
		$order_by = $_GET['order'];
	} else {
		$order_by = "";
	}

	$Users = new Users($DB);
	$Courses = new Courses($DB);
	$Tests = new Tests($DB);

	$user_name = $_SESSION['userName'];
	//get all columns from user's row
	$user_info = $Users->fetchUser($user_name);
	$user_id = $user_info['id'];

	$enrolled_courses = $Courses->fetchEnrolledCourses($user_name, "student");
	$enrolled_course_id = $enrolled_courses['courseId'];
	$test_id = $Tests->fetchByName($_GET['test_name']);
	$test_id = $test_id['testId'];

	$tests = $Courses->fetchAllTestInfo($enrolled_course_id);

	switch ($_GET['action']) {
		case 'all':
			$test_results[] = $Tests->getResults($user_id, -1, $order_by);
			break;
		case 'specific':
			$test_results[] = $Tests->getResults($user_id, $test_id, $order_by);
			break;
		default:
			$Utils->closeAndExit("Invalid action");
	}

	$table_content = "";
	//generate the table
	foreach ($test_results as $result) {
		$table_content .= "<tr>
	<td>{$result['attempt']}</td>
	<td>{$result['submitted']}</td>
	<td>{$result['grade']}</td>
	<td>{$result['percentage']}</td>
	<td>{$result['number_correct']}</td>
	<td>{$result['number_incorrect']}</td>
</tr>";
	}

	$table_html = "<table>
	<tr>
		<th>Attempt #</th>
		<th>Date Submitted</th>
		<th>Grade</th>
		<th>Percentage</th>
		<th>Number Correct</th>
		<th>Number Incorrect</th>
	</tr>
	$table_content
</table>";

	$Utils->closeAndExit($table_html);