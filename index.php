<?
	/**
	 * This is where the user will take a quiz. The user is redirected to the signin page if they are not already signed in
	 */
	require_once("autoloader.php");
	session_start();

	$user_name = $_SESSION['userName'];
	if (!$Utils->checkIsSet(array($user_name), array(""))) { //if not logged in already, go to login page
		$Utils->redirectTo('signin.php');
	}

	/*
	 * Class declarations
	 */
	$Tests = new Tests($DB);
	$Users = new Users($DB);
	$UI = new UI($_SERVER['PHP_SELF'], "Index - EKULMS");
	$Courses = new Courses($DB);

	$errors = array();
	$user = $Users->fetchUser($user_name);

	/*
	 * The very unlikely chance that the user isn't in the database
	 */
	if (!$user) {
		array_push($errors, "Well this is awkward... The user was not found in the database");
	}

	$enrolled_courses = $Courses->fetchEnrolledCourses($user_name, 'student');
	/*
	 * If the user isn't enrolled in a course, prompt them to enroll in one
	 */
	if (!$enrolled_courses) {
		array_push($errors, "It appears you are not enrolled in a course. Please <a href='account.php'>enroll in one</a>");
	}

	/*
	 * Build a list of tests
	 */
	$list_of_tests = array();
	foreach ($enrolled_courses as $course) {
		$allByCourseId = $Tests->fetchAllByCourseId($course['courseId']);
		if ($allByCourseId) {
			$list_of_tests[$course['courseName']][] = $allByCourseId;
		} else {
			continue;
		}
	}

	/*
	 * Count the errors and build the error message
	 */
	$errors_to_display = "";
	if (count($errors) > 0) {
		foreach ($errors as $error) {
			$errors_to_display .= "$error <br />";
		}
	}

	/*
	 * Get the tests available to the student and build the list
	 */
	$test_options = "<option>No tests found</option>";
	if (count($list_of_tests) > 0) {
		$test_options = "";
		foreach ($list_of_tests as $course) {
			foreach ($course as $test) {
				foreach ($test as $t) {
					$test_options .= "<option>{$t['testName']}</option>";
				}
			}
		}
	}

	/*
	 * Update the template variables
	 */
	/*
	 * Set the template variables with the new values, parse the header template, and show the header and footer
	 */
	$UI->addToTemplateVariables(array("errors_to_display" => $errors_to_display,
	                                  "test_options"      => $test_options,
	                                  "description"       => "Tests for EKULMS",
	                                  "home_active"       => "class='active'"))
		->executeHeaderTemplate("index_header")
		->show("header")->show("footer");
	exit();