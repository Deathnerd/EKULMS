<?
	/**
	 * This page will provide an interface for instructor users to create quizzes.
	 * @todo check if user is an admin or an instructor. Currently checking only for admin
	 */
	require_once("autoloader.php");
	session_start();
	$User = new Users($DB);
	$Tests = new Tests($DB);
	$Courses = new Courses($DB);

	if (!$Utils->checkIsSet(array($_SESSION['userName']), array(""))) { //if there isn't a user logged in, send them to the login page
		$Utils->redirectTo("signin.php");
	}
	if (!$User->isAdmin($_SESSION['userName']) || !$User->isAnInstructor($_SESSION['userName'])) { //if user isn't an admin, send them to the index
		$Utils->redirectTo('index.php');
	}
	$UI = new UI($_SERVER['PHP_SELF'], "Create Test - EKULMS");
	$UI->executeHeaderTemplate('header_v2')->show('header');
?>
	<input type="hidden" value="make" id="action"/>
	<label for="courseName">Course Name: </label>
	<select id="courseName"><?
			$instructedCourses = $Courses->fetchEnrolledCourses($_SESSION['userName'], 'instructor');
			foreach ($instructedCourses as $course) {
				$val = $course['courseId'] . ":" . $course['courseName'];
				echo "<option val='$val'>{$course['courseId']} -- {$course['courseName']}</option>";
			}
		?>
	</select>
	<label>
		<select id="coursesDropdown">
			<option>Select a course from the left</option>
		</select>
	</label>
	<input type="button" value="Load" id="load2">
	<input value="New Test" id="newTest" type="button"/>
	<input type="button" value="Download All Quizzes" id="download">
	<br/>
	<label for="quizName">Quiz name</label><input type="text" value="Quiz 1" id="quizName">
	<input type="button" value="Add question" class="question_add">
	<input type="button" value="Remove question" class="question_remove">
	<input type="button" value="Save Quiz" id="saveQuiz">

	<div class="small_padding question" id="question_1">
		<!-- Begin question prompt and buttons -->
		<p class="question_label">Question 1</p>
		<label>
			<textarea name="prompt" cols="40" rows="5">Input question here</textarea>
		</label>
		<br>
		<input type="button" value="Add Choice" id="add_choice" class="choice_add">
		<input type="button" value="Remove Last Choice" id="remove_choice" class="choice_remove"><br>
		<!-- End question prompt and buttons -->
		<!-- Begin choices table -->
		<table width="430" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="30" scope="col">#</th>
				<th width="323" scope="col">Value</th>
				<th width="58" scope="col">Correct?</th>
			</tr>
			<tr>
				<td width="30">
					<div align="center">1</div>
				</td>
				<td width="323">
					<label>
						<input type="text" class="choice" value="Enter choice">
					</label>
				</td>
				<td width="58">
					<div align="center">
						<label>
							<input name="" type="checkbox" class="correctBox" value="">
						</label>
					</div>
				</td>
			</tr>
		</table>
		<!-- End choices table -->
	</div>
	<script src="js/create.js"></script>
	<div style="left: 30px"></div>
<?
	$UI->show('footer');
	exit();
?>