<?
	/**
	 * This script handles the logic of signing up a user. If the user exists, it returns with a string saying so. Otherwise, the user is added to the database and a success message is returned
	 */
	require_once('autoloader.php');
	$Utilities = new Utilities();
	session_start();
	if ($Utilities->checkIsSet(array($_SESSION['userName']),
	                           array("logged_in"))
	) { //if a user is already signed in
		exit();
	}

	if (!$Utilities->checkIsSet(array($_GET['userName'], $_GET['password'], $_GET['']),
	                            array("No user name received", "No password received"))
	) {
		session_destroy();
		exit();
	}

	$DB = new Db();
	$Users = new Users($DB);
	//check if user exists in database
	if (!$Users->userExists($_GET['userName'])) { //if user does not already exist, then attempt to create it
		if ($Users->create($_GET['userName'], $_GET['password'], $_GET['email'])) { //if user created successfully
			$userInfo = $Users->fetchUser($_SESSION['userName']);
			$_SESSION['id'] = $userInfo['id']; //remember the user id
			$_SESSION['userName'] = $userInfo['userName']; //remember the actual user name
			$Utilities->closeAndExit($DB, "Success!");
		}
		$Utilities->closeAndExit($DB, "Failed to create user!");
	}
	$Utilities->closeAndExit($DB, "User already exists!");