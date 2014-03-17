<?
	//account.php
	//account management page for a user
	session_start();
	if(!isset($_SESSION['userName'])){ //if not logged in, go to the login page
		header('Location: login.php');
	}

	if(!is_file('requires/Users.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Users.php! Check your installation");
	}
	require_once('requires/Users.php')//import the user database methods

	$User = new Users;
 
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
	</head>
	<body>
		<header id="topNav">
			<div id="logo">
			</div>
			<div id="dropdown">
			</div>
			<p id="pageTitle"></p>
		</header>
		<p id="userGreeting"><?
			echo "Hello, ".$_SESSION['userName']."!";
		 </p>
		<div class="bodyContainer">
			
		</div>
	</body>
</html>