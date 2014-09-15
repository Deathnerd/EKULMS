<?
	/**
	 * This is where the user will take a quiz. The user is redirected to the signin page if they are not already signed in
	 */
	require_once("autoloader.php");
	$Tests = new Tests($DB);
	$Utils = new Utilities($DB);
	session_start();
	$user_name = $_SESSION['userName'];
	if (!$Utils->checkIsSet(array($user_name), array(""))) { //if not logged in already, go to login page
		$Utils->redirectTo('signin.php');
	}
	$Users = new Users($DB);

	$errors = array();
	$user = $Users->fetchUser($user_name);
	if (!$user) {
		array_push($errors, "Well this is awkward... The user was not found in the database");
	}
	$Courses = new Courses($DB);
	$enrolled_courses = $Courses->fetchEnrolledCourses($user_name, 'student');
	if (!$enrolled_courses) {
		array_push($errors, "It appears you are not enrolled in a course. Please <a href='account.php'>enroll in one</a>");
	}
	$list_of_tests = array();
	foreach ($enrolled_courses as $course) {
		$allByCourseId = $Tests->fetchAllByCourseId($course['courseId']);
		if ($allByCourseId) {
			$list_of_tests[$course['courseName']][] = $allByCourseId;
		} else {
			continue;
		}
	}
?>
	<!DOCTYPE html>
<html>
	<head>
		<title>Practice Quizzes - EKULMS</title>
		<meta name="description" content="Practice Exams for CSC 185">
		<meta name="author" content="Wes Gilleland">
		<meta name="published" content="TODO">
		<script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		<link type="text/css" rel="stylesheet" href="css/reset.css">
		<link type="text/css" rel="stylesheet" href="css/main.css">
	</head>
<body>
<header id="topNav">
	<div id="logo">
	</div>
	<div id="dropdown">
	</div>
	<div id="errors" style="float: right; width: auto;">
		<?
			if (count($errors) > 0) {
				foreach ($errors as $error) {
					echo "$error <br />";
				}
			}
		?>
	</div>
	<p id="pageTitle"></p>
	<select><?

			if (count($list_of_tests) > 0) {
				foreach ($list_of_tests as $course) {
					foreach ($course as $test) {
						foreach ($test as $t) {
							$test_id = $t['testId'];
							$test_name = $t['testName'];
							echo "<option value='$test_id'>$test_name</option>";
						}
					}
				}
			} else {
				echo "<option>No tests found</option>";
			}
		?>
	</select>
	<button type="button" id="load">Load</button>
</header>
<p id="userGreeting">
	<? echo "Hello, $user_name!"; ?>
</p>
<?
	$UI->show("footer");
	$DB->close();
?>