<?
	//login.php
	//logs in a user

	//input check block
	require('dbFunctions.php');
	session_start();

	if (isset($_GET['userName'])){
		$_SESSION['userName'] = $_GET['userName'];
	} else {
		echo "No user name received";
		exit();
	}
	if(isset($_GET['password'])){
		$_SESSION['password'] = $_GET['password'];
	} else {
		echo "No password received";
		exit();
	}

	//check if user exists in database
	$Users = new Users; //Users class contains functions related to user interaction/manipulation
?>