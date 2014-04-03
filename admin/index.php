<?
	/**
	 * A simple admin interface
	 * @todo everything
	 */
	session_start();
	if (!isset($_SESSION['userName']) || $_SESSION['admin'] != '1') { //if not logged in, go to the login page
		header('Location: ../signin.php');
	}

	require('../requires/header.php');
?>
<p id="userGreeting">
	<? echo "Hello, " . $_SESSION['userName'] . "!"; ?>
</p>

<div class="bodyContainer">
	<div id="courseSetup">
		<p>Course id:</p>
		<input type="text" id="courseId">
		<br>

		<p>Course name:</p>
		<input type="text" id="courseName">
		<br>

		<p>Course description (optional):</p>
		<textarea cols="40" rows="5" id="description"></textarea>
		<br>
		<input type="button" id="addCourse" value="Add Course">
		<br>

		<div id="message"></div>
	</div>
	<div id="addUserToCourse">
		<p>Username:</p>
		<input type="text" id="userName">
		<br>

		<p>Course Id:</p>
		<input type="text" id="courseId">
		<br>

		<p>Instructor:</p>
		<input type="checkbox" id="instructor">
		<br>
		<input type="button" id="addUser" value="Add User to Course">

		<div id="message"></div>
	</div>
	<br>

	<div id="list">
		<input type="button" id="listCourses" value="List courses">

		<div id="listResults" style="display: hidden">
			<table>
				<tbody>
				<tr>
					<th>Course Name</th>
					<th>Course Id</th>
					<th>Course Description</th>
				</tr>
			</table>
			</tbody>
		</div>
	</div>
</div>
<?
	require('requires/footer.php');
?>