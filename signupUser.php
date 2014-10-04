<?
	/**
	 * This script handles the logic of signing up a user. If the user exists, it returns with a string saying so. Otherwise, the user is added to the database and a success message is returned
	 */
	require_once('autoloader.php');
	session_start();
	if (!$Utils->checkIsSet(array($_GET['userName'], $_GET['password'], $_GET['email']),
		array("No user name received", "No password received", "No email received"))
	) {
		session_destroy();
		exit();
	}
	$Utils->exitWithMessage("Registration isn't open yet!");
	$Users = new Users($DB);
	//check if user exists in database
	if (!$Users->userExists($_GET['userName'])) { //if user does not already exist, then attempt to create it
		if ($Users->create($_GET['userName'], $_GET['password'], $_GET['email'])) { //if user created successfully
			$Utils->exitWithMessage("Success! You may now log in with your credentials");
		}
		$Utils->exitWithMessage("Failed to create user!");
	}
	$Utils->exitWithMessage("User already exists!");