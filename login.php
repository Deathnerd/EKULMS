<?
	/**
	 * This page logs in the user and requires the User.php file.
	 */
	error_reporting(E_ALL);
	require_once('utils/utilities.php');
	$Utils = new Utilities();
	session_start();

	$Utils->checkFile('requires/Users.php', __FILE__, __LINE__);
	require_once('requires/Users.php'); //import the user database methods

	$sessionUserName = $_SESSION['userName'];
	if (isset($sessionUserName)) {
		echo "$sessionUserName already logged in. Please log out first";
		exit();
	}

	$userName = $_GET['userName'];
	if(!isset($userName)) {
		echo "No user name received";
		session_destroy();
		exit();
	} elseif (!strlen($userName) > 0){
		echo "User Name cannot be 0 characters";
		session_destroy();
		exit();
	}

	$password = $_GET['password'];
	if(!isset($password)) {
		echo "No user name received";
		session_destroy();
		exit();
	} elseif (!strlen($password) > 0){
		echo "Password cannot be 0 characters";
		session_destroy();
		exit();
	}

	$Users = new Users; //Users class contains functions related to user interaction/manipulation

	//check if user exists in database
	if ($Users->userExists($userName)) { //if user exists
		if (!$Users->checkPassword($userName, $password)) { //if the password is incorrect
			echo "Incorrect password";
			$Users->close();
			session_destroy();
			exit();
		}
		//if the user exists and the password is correct
		echo "Success!";
		$userInfo = $Users->fetchUser($userName);
		$_SESSION['id'] = $userInfo['id']; //remember the user id
		$_SESSION['userName'] = $userInfo['userName']; //remember the actual userName
		$_SESSION['admin'] = $userInfo['admin'];
		unset($_SESSION['password']); //trash the password
		$Users->close(); //close the database connection
		exit();
	} else {
		echo "User not found. Have you created an account?";
		session_destroy();
		$Users->close();
		exit();
	}