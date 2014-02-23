<?
	//login.php
	//logs in a user

	//header('Content-type: application/text'); //set the data type to text

	require('dbFunctions.php'); //import the database functions
	session_start(); //start the session

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

	$Users = new Users; //Users class contains functions related to user interaction/manipulation
	$Db = new Db; //base class containing generic database functions

	//check if user exists in database
	if($Users->checkUserExists($_SESSION['userName'])){ //if user exists
		if(!$Users->checkPassword($_SESSION['userName'], $_SESSION['password'])){ //if the password is incorrect
			echo "Incorrect password supplied";
			$Db->close();
			session_destroy();
			exit();
		}
		//if the user exists and the password is correct
		echo "Success!";
		$userInfo = $Users->fetchUser($_SESSION['userName']);
		$_SESSION['id'] = $userInfo['id']; //remember the user id
		$_SESSION['userName'] = $userInfo['userName']; //remember the actual userName
		unset($_SESSION['password']);//trash the password
		$Db->close();//close the database connection
		exit();
	} else {
		echo "User not found. Have you created an account?";
		session_destroy();
		$Db->close();
		exit();
	}
?>