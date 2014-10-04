<?
	/**
	 * Lists a user's account information along with links to course pages and statistitcs. The usser will be redirected to the login page if they are not currently logged in
	 */
	require_once("autoloader.php");
	session_start();

	if (!$Utils->checkIsSet(array($_SESSION['userName']), array(""))) { //if not logged in, go to the login page
		$Utils->redirectTo("signin.php");
	}

	$userName = $_SESSION['userName'];

	$Courses = new Courses($DB);
	$Tests = new Tests($DB);
	$UI = new UI($_SERVER['PHP_SELF'], "User Account - EKULMS");

	$UI->addToTemplateVariables(["account_active"=> "class='active'"])->executeHeaderTemplate('header_v2')->show("header");
	$userCourses = $Courses->fetchEnrolledCourses($userName, 'student');
	$allCourses = $Courses->fetchAll();
?>
	<div class="bodyContainer">
		<div id="signupCourse">
			<p>Course Id:</p>
			<label for="courseSignupDropdown"></label>
			<select name="courseSignupDropdown" id="courseSignupDropdown">
				<option value="none">Select a Course</option>
				<?
					foreach ($allCourses as $course) {
						?>
						<option value="<?= $course['courseId']; ?>"><?= $course['courseName']; ?></option>
					<?
					}
				?>
			</select>
			<br>
			<input type="button" id="addUser" value="Sign up for course">
			<div id="message"></div>
		</div>
	</div>
	<a href="index.php">Take a quiz</a>
	<div id="userStatistics">
		<span id="reporting_selection" class="thin_border small_padding">
			<label for="report_course">Select a course: </label>
			<select name="report_course" id="report_course">
				<?
					foreach ($userCourses as $course) {
						echo "<option>{$course['courseId']} -- {$course['courseName']}</option>";
					}
				?>
			</select>
			<label for="tests"></label>
			<select name="tests" id="tests">
				<option value="">Select a course first</option>
			</select>
			<input type="button" id="fetch_by_test" disabled="true" value="Fetch history for test">
		</span>

		<div id="statsResults">
			<table id="statsResultsTable" class="table table-bordered"></table>
		</div>
	</div>
<?
	$UI->show("footer");
	exit();
?>