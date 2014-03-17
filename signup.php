<?
	//signup.php
	//user account creation page
	session_start();

	if(isset($_SESSION['userName'])){ //if there's already a user logged in, redirect them to the index
		header('Location: index.php');
	}
 
<!doctype html>
<html>
	<head>
		<title>Sign Up</title>
		<meta name="description" content="Sign Up page">
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
		</header>
		<div id="bodyContainer">
			<p>User Name:</p>
			<input type="text" id="userName"><br/>
			<p>Password:</p>
			<input type="password"><br/>
			<p>Special key:</p>
			<input type="text" id="userKey"><br/>
			<input type="button" value="Sign up" id="signUpButton"><br/>
			<p id="message" style="display: none">Default text</p>
		</div>
	</body>
</html>