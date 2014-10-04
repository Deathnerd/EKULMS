<?
	/**
	 * A simple admin interface
	 */
	require_once("../autoloader.php");
	session_start();
	if (!isset($_SESSION['userName']) || $_SESSION['admin'] != '1') { //if not logged in, go to the login page
		$Utils->redirectTo('Location: ../signin.php');
	}
	$UI = new UI($_SERVER['PHP_SELF'], "Administrator Page - EKULMS");
	if($_SESSION['admin']){
		$base_url = SITE_ROOT;
		$UI->addToTemplateVariables(array("admin_link" => "<li class='active'><a href='$base_url/admin'><span class='glyphicon glyphicon-wrench'></span> Admin Area</a></li>"));
	}
	$UI->executeHeaderTemplate("header_v2")->show('header');
?>
	<script src="../js/admin.js"></script>

	<div id="courseSetup" class="col-md-6">
		<label for="courseId">Course id:</label>
		<input type="text" id="courseId" class="form-control">
		<br/>

		<label for="courseName">Course name:</label>
		<input type="text" id="courseName" class="form-control">
		<br/>

		<label for="description">Course Description (optional):</label>
		<textarea cols="40" rows="5" id="description" class="form-control"></textarea>
		<br/>

		<button class="btn btn-default" id="addCourse">Add Course</button>
		<br/>

		<div id="message"></div>
	</div>
	<div id="addUserToCourse" class="col-md-6">
		<label for="userName">Username:</label>
		<input type="text" id="userName" class="form-control">
		<br/>

		<label for="courseId">Course Id:</label>
		<input type="text" id="courseId" name="courseId" class="form-control">
		<br/>

		<label for="instructor">Instructor:</label>
		<input type="checkbox" id="instructor" class="checkbox checkbox-default">

		<br/>
		<button class="btn btn-default" id="addUser">Add User to Course</button>
	</div>
	<br/>

	<div id="message"></div>
<?
	$UI->show('footer');