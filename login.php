<?
	/**
	 * This page logs in the user and requires the User.php file.
	 */
	require_once("autoloader.php");
	$Utils = new Utilities();
	$DB = new Db();
	$Users = new Users($DB);
	session_start();

	$sessionUserName = $_SESSION['userName'];
	$userName = $_GET['userName'];
	$password = $_GET['password'];
	if ($Utils->checkIsSet(array($sessionUserName), array("$sessionUserName already logged in. Please log out first"))
	|| !$Utils->checkIsSet(array($userName, $password), array("No user name received", "No password recieved"))) {
		session_destroy();
		$Utils->closeAndExit($DB);
	}

	//check if user exists in database
	if ($Users->userExists($userName)) { //if user exists
		if (!$Users->checkPassword($userName, $password)) { //if the password is incorrect
			session_destroy();
			$Utils->closeAndExit($DB, "Incorrect password");
		}
		//if the user exists and the password is correct
		$message = "Success!";
		$userInfo = $Users->fetchUser($userName);

		//set the id, userName, and admin values for the session
		foreach($userInfo as $key => $value){
			$_SESSION[$key] = $value;
		}
		unset($_SESSION['password']); //trash the password
	} else {
		$message = "User not found. Have you created an account?";
		session_destroy();
	}
	$Utils->closeAndExit($DB, $message);