<?php

	/**
	 * This is where the user will take a quiz. The user is redirected to the signin page if they are not already signed in
	 */
	session_start();
	if (!isset($_SESSION['userName'])) { //if not logged in already, go to login page
		header('Location: signin.php');
	}
?>
	<!DOCTYPE html>
<html>
	<head>
		<title>CSC 185 Practice Exams</title>
		<meta name="description" content="Practice Exams for CSC 185">
		<meta name="author" content="Wes Gilleland">
		<meta name="published" content="TODO">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="scripts/main.js"></script>
		<link type="text/css" rel="stylesheet" href="styles/reset.css">
		<link type="text/css" rel="stylesheet" href="styles/main.css">
	</head>
<body>
<header id="topNav">
	<div id="logo">
	</div>
	<div id="dropdown">
	</div>
	<p id="pageTitle"></p>
	<select><?
			$files = glob('quizzes/*.json'); //find all the quiz files
			//populate the dropdown
			foreach ($files as $file) {
				echo '<option>' . $file . '</option>';
			} ?>
	</select>
	<button type="button" id="load">Load</button>
</header>
<p id="userGreeting">
	<? echo "Hello, " . $_SESSION['userName'] . "!"; ?>
</p>
<?
	require('requires/footer.php');
?>