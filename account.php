<?
	/**
	 * Lists a user's account information along with links to course pages and statistitcs. The usser will be redirected to the login page if they are not currently logged in
	 * @todo everything
	 */

	session_start();
	if (!isset($_SESSION['userName'])) { //if not logged in, go to the login page
		header('Location: login.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="description" content="Practice Exams for CSC 185">
	<meta name="author" content="Wes Gilleland">
	<meta name="published" content="TODO">
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="scripts/main.js"></script>
	<link type="text/css" rel="stylesheet" href="styles/reset.css">
	<link type="text/css" rel="stylesheet" href="styles/main.css">
	<link type="text/css" rel="stylesheet" href="styles/admin.css">
</head>
<body>
<header id="topNav">
	<!-- <div id="logo">
	</div>
	<div id="dropdown">
	</div>
	<p id="pageTitle"></p> -->
	<a href="courseSignup.php">Sign up for a course</a>
	<a href="statistics.php">View your statistics (work in progress)</a>
	<a href="logout.php">Log out</a>
</header>
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
</body>
</html>