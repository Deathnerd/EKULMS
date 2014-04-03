<?
	/**
	 * Lists a user's account information along with links to course pages and statistitcs. The usser will be redirected to the login page if they are not currently logged in
	 * @todo everything
	 */

	session_start();
	if (!isset($_SESSION['userName'])) { //if not logged in, go to the login page
		header('Location: login.php');
	}
	require('requires/header.php');
?>
<p id="userGreeting">
	<? echo "Hello, " . $_SESSION['userName'] . "!"; ?>
</p>

<div class="bodyContainer">
	<div id="signupCourse">
		<p>Username:</p>
		<input type="text" id="userName">
		<br>

		<p>Course Id:</p>
		<input type="text" id="courseId">
		<br>
		<input type="button" id="addUser" value="Add User to Course">

		<div id="message"></div>
	</div>
</div>
<?
	require('requires/footer.php');
?>