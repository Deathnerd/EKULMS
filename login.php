<?
	/**
	 * This page logs in the user and requires the User.php file.
	 */
	require_once('requires/Globals.php');
	session_start();

	$sessionUserName = $_SESSION['userName'];
	if (isset($sessionUserName)) {
		echo "$sessionUserName already logged in. Please log out first";
		$DB->close();
		exit();
	}

	$userName = $_GET['userName'];
	if(!isset($userName)) {
		echo "No user name received";
		session_destroy();
		$DB->close();
		exit();
	} elseif (!strlen($userName) > 0){
		echo "User Name cannot be 0 characters";
		session_destroy();
		$DB->close();
		exit();
	}

	$password = $_GET['password'];
	if(!isset($password)) {
		echo "No user name received";
		session_destroy();
		$DB->close();
		exit();
	} elseif (!strlen($password) > 0){
		echo "Password cannot be 0 characters";
		session_destroy();
		$DB->close();
		exit();
	}

	//check if user exists in database
	if ($Users->userExists($userName)) { //if user exists
		if (!$Users->checkPassword($userName, $password)) { //if the password is incorrect
			echo "Incorrect password";
			$DB->close();
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
	} else {
		echo "User not found. Have you created an account?";
		session_destroy();
	}
	$DB->close();
	exit();