<?
	/**
	 * Lists a user's account information along with links to course pages and statistitcs. The usser will be redirected to the login page if they are not currently logged in
	 */
	require_once('requires/Globals.php');
	session_start();

	$userName = $_SESSION['userName'];
	if (!isset($userName)) { //if not logged in, go to the login page
		header('Location: signin.php');
		exit();
	}
	require_once('requires/header.php');
?>
	<p id="userGreeting">
		<?= "Hello, $userName!"; ?>
	</p>
	<div class="bodyContainer">
		<div id="signupCourse">
			<p>Course Id:</p>
			<input type="text" id="courseId">
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
		<select name="userCourses" id="courses">
			<?
				$userCourses = $Courses->fetchEnrolledCourses($userName, 'student');
				foreach($userCourses as $course){
					$courseName = $course['courseName'];
					$courseId = $course['courseId'];
					echo "<option value='$courseId'>$courseId -- $courseName</option>";
				}
			?>
		</select>
	</div>
<?
	require_once('requires/footer.php');
	$DB->close();
?>