<?
	/**
	 * Lists a user's account information along with links to course pages and statistitcs. The usser will be redirected to the login page if they are not currently logged in
	 * @todo everything
	 */

	session_start();
	if (!isset($_SESSION['userName'])) { //if not logged in, go to the login page
		header('Location: login.php');
	}
	require_once('requires/header.php');
?>
	<p id="userGreeting">
		<? echo "Hello, " . $_SESSION['userName'] . "!"; ?>
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
<?
	require_once('requires/footer.php');
?>