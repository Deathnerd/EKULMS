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
	$UI = new UI($_SERVER['PHP_SELF'], "User Account - EKULMS", $DebugBar);

	$UI->show("header");
?>
	<p id="userGreeting">
		<?= "Hello, $userName!"; ?>
	</p>
	<div class="bodyContainer">
		<div id="signupCourse">
			<p>Course Id:</p>
			<label for="courseSignupDropdown"></label>
			<select name="courseSignupDropdown" id="courseSignupDropdown">
				<option value="none">Select a Course</option>
				<?
					$allCourses = $Courses->fetchAll();
					foreach ($allCourses as $course) {
						?>
						<option value="<?= $course['courseId']; ?>"><?= $course['courseName']; ?></option>
					<?
					}
				?>
			</select>
<!--			<input type="text" id="courseId">-->
			<br>
			<input type="button" id="addUser" value="Sign up for course">

			<div id="message"></div>
		</div>
	</div>
	<a href="index.php">Take a quiz</a>
	<div id="listStudentCourses">
		<input type="button" id="listCourses" value="List courses">
		<table>
			<tr>
				<th>Course Id</th>
				<th>Course Name</th>
				<th>Course Description</th>
			</tr>
		</table>
	</div>
	<div id="userStatistics">
		<input id="getStats" value="Get Course Stats" type="button"/>
		<label for="courses"></label>
		<select name="userCourses" id="courses">
			<?
				$userCourses = $Courses->fetchEnrolledCourses($userName, 'student');
				foreach ($userCourses as $course) {
					echo "<option>{$course['courseId']} -- {$course['courseName']}</option>";
					$tests = $Tests->fetchAllByCourseId($course['courseId']);
					if (!$tests) {
						echo "<option> -- No tests found</option>";
					} else {
						foreach ($tests as $test) {
							echo "<option value='{$course['courseId']}|{$test['testId']}'>-- {$test['testName']}</option>";
						}
					}
				}
			?>
		</select>

		<span id="reporting_selection">

			<label for="report_course">Select a course: </label>
			<select name="report_course" id="report_course"></select>
			<label for="tests" class="hide"></label>
			<select name="tests" id="tests" class="hide"></select>
		</span>

		<div id="statsResultsTable" class="hide"></div>
	</div>
<?
	$UI->show("footer");
?>