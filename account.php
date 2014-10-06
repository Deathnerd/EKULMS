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

	$UI = new UI($_SERVER['PHP_SELF'], "User Account - EKULMS");
	if(boolval($_SESSION['admin'])){
		$base_url = SITE_ROOT;
		$UI->addToTemplateVariables(array("admin_link" => "<li><a href='$base_url/admin'><span class='glyphicon glyphicon-wrench'></span> Admin Area</a></li>"));
	}

	$UI->addToTemplateVariables(["account_active" => 'active'])->executeHeaderTemplate('header_v2')->show("header");
	$userCourses = $Courses->fetchEnrolledCourses($userName, 'student');
	$allCourses = $Courses->fetchAll();
?>
	<div id="signupCourse" class="col-md-6">
		<label for="courseSignupDropdown">Sign up for a course</label>
		<select name="courseSignupDropdown" id="courseSignupDropdown" class="form-control">
			<option value="none">Select a Course</option>
			<?
				foreach ($allCourses as $course) {
					?>
					<option value="<?= $course['courseId']; ?>"><?= $course['courseName']; ?></option>
				<?
				}
			?>
		</select>
		<br/>
		<button id="addUser" class="btn btn-default pull-right">Sign up for course</button>

		<div id="message"></div>
	</div>
	<span class="clearfix"></span>
	<br/>
	<div id="userStatistics" class="col-md-6">
		<div id="reporting_selection" class="small_padding form-group-sm">
			<label for="report_course">Select a course: </label>
			<select name="report_course" id="report_course" class="form-control">
				<?
					foreach ($userCourses as $course) {
						echo "<option>{$course['courseId']} -- {$course['courseName']}</option>";
					}
				?>
			</select>
			<label for="tests"></label>
			<select name="tests" id="tests" class="form-control">
				<option value="">Select a course first</option>
			</select>
			<br/>
			<button class="btn btn-default pull-right" id="fetch_by_test" disabled="true">
				Fetch history for test
			</button>
		</div>
	</div>
	<div id="statsResults">
		<table id="statsResultsTable" class="table table-bordered"></table>
	</div>
<?
	$UI->show("footer");
	exit();
?>