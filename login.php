<?
	/**
	 * This page logs in the user and requires the User.php file.
	 */
	require_once("autoloader.php");
	$Utils = new Utilities($DB);
	$Users = new Users($DB);
	session_start();

	$sessionUserName = $_SESSION['userName'];
	$userName = $_GET['userName'];
	$password = $_GET['password'];
	if (!$Utils->checkIsSet(array($userName, $password), array("No user name received", "No password recieved"))) {
		session_destroy();
		$Utils->closeAndExit();
	}

	//check if user exists in database
	if ($Users->userExists($userName)) { //if user exists
		if (!$Users->login($userName, $password)) { //if the password is incorrect
			session_destroy();
			$message = "Incorrect password";
		} else {
			//if the user exists and the password is correct
			$message = "Success!";
		}
	} else {
		$message = "User not found. Have you created an account?";
		session_destroy();
	}
	$Utils->closeAndExit($message);