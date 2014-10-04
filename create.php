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
	if ($_SESSION['admin']) {
		$base_url = SITE_ROOT;
		$UI->addToTemplateVariables(array("admin_link" => "<li><a href='$base_url/admin'><span class='glyphicon glyphicon-wrench'></span> Admin Area</a></li>"));
	}
	$UI->executeHeaderTemplate('header_v2')->show('header');
?>
	<input type="hidden" value="make" id="action"/>

	<div class="col-md-6">
		<label for="courseName">Course Name: </label>
		<select id="courseName" class="form-control"><?
				$instructedCourses = $Courses->fetchEnrolledCourses($_SESSION['userName'], 'instructor');
				foreach ($instructedCourses as $course) {
					$val = $course['courseId'] . ":" . $course['courseName'];
					echo "<option val='$val'>{$course['courseId']} -- {$course['courseName']}</option>";
				}
			?>
		</select>
		<br/>
		<select id="coursesDropdown" class="form-control">
			<option>Select a course from the left</option>
		</select>
		<br/>
		<button id="newTest" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> New Test</button>
		<button id="load2" class="btn btn-info"><span class="glyphicon glyphicon-download"></span> Load</button>
		<button id="download" class="btn btn-info">
			<span class="glyphicon glyphicon-download"></span>
			Download All Quizzes
		</button>
	</div>

	<div class="col-md-6">
		<label for="quizName">Quiz ame</label>
		<input type="text" value="Quiz 1" id="quizName" class="form-control">
		<br/>
		<button class="question_add btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add Question</button>
		<button class="question_remove btn btn-primary"><span class="glyphicon glyphicon-remove"></span> Remove Question</button>
		<button id="saveQuiz" class="btn btn-info"><span class="glyphicon glyphicon-save"></span> Save Quiz</button>
	</div>
	<span class="clearfix"></span>
	<div class="index-head-spacer"></div>
	<div class="small_padding question" id="question_1">
		<!-- Begin question prompt and buttons -->
		<p class="question_label">Question 1</p>
		<label>
			<textarea name="prompt" cols="40" rows="5">Input question here</textarea>
		</label>
		<br/>
		<input type="button" value="Add Choice" id="add_choice" class="choice_add">
		<input type="button" value="Remove Last Choice" id="remove_choice" class="choice_remove">
		<br/>
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