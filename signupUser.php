<?
	/**
	 * This script handles the logic of signing up a user. If the user exists, it returns with a string saying so. Otherwise, the user is added to the database and a success message is returned
	 */
	require_once('requires/Globals.php');

	if (!$Utilities->checkIsSet(array($_SESSION['userName']),
	                            array(""))
	) { //if a user is already signed in
		header('Location: index.php');
		exit();
	}

	session_start();
	if (!$Utilities->checkIsSet(array($_GET['userName'], $_GET['password']),
	                            array("No user name received", "No password received"))
	) {
		session_destroy();
		exit();
	}
	$_SESSION['password'] = $_GET['password'];
	$_SESSION['userName'] = $_GET['userName'];
	//check if user exists in database
	if (!$Users->userExists($_SESSION['userName'])) { //if user does not already exist, then attempt to create it
		if ($Users->create($_SESSION['userName'], $_SESSION['password'])) { //if user created successfully
			$userInfo = $Users->fetchUser($_SESSION['userName']);
			unset($_SESSION['password']); //trash the password
			$_SESSION['id'] = $userInfo['id']; //remember the user id
			$_SESSION['userName'] = $userInfo['userName']; //remember the actual user name
			echo "Success!";
			$Db->close();
			exit();
		}
		echo "Failed to create user!";
		session_destroy();
		$Db->close();
		exit();
	} else {
		echo "User already exists!";
		session_destroy();
		$Db->close();
		exit();
	}