<?
	/**
	* This page logs in the user and requires the User.php file.
	*/
	session_start();
	if(!is_file('requires/Users.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Users.php! Check your installation");
	}
	require_once('requires/Users.php');//import the user database methods
	if(isset($_SESSION['userName'])){
		echo $_SESSION['userName']." already logged in. Please log out first";
		exit();
	}
	session_start();//start the session

	if (isset($_GET['userName']) && strlen($_GET['userName']) > 0){
		$_SESSION['userName'] = $_GET['userName'];
	} else {
		echo "No user name received";
		session_destroy();
		exit();
	}
	if(isset($_GET['password']) && strlen($_GET['password']) > 0){
		$_SESSION['password'] = $_GET['password'];
	} else {
		echo "No password received";
		session_destroy();
		exit();
	}

	$Users = new Users;//Users class contains functions related to user interaction/manipulation

	//check if user exists in database
	if($Users->checkUserExists($_SESSION['userName'])){ //if user exists
		if(!$Users->checkPassword($_SESSION['userName'], $_SESSION['password'])){ //if the password is incorrect
			echo "Incorrect password";
			$Users->parent::close();
			session_destroy();
			exit();
		}
		//if the user exists and the password is correct
		echo "Success!";
		$userInfo = $Users->fetchUser($_SESSION['userName']);
		$_SESSION['id'] = $userInfo['id'];//remember the user id
		$_SESSION['userName'] = $userInfo['userName'];//remember the actual userName
		$_SESSION['admin'] = $userInfo['admin'];
		unset($_SESSION['password']);//trash the password
		$Users->parent::close();//close the database connection
		exit();
	} else {
		echo "User not found. Have you created an account?";
		session_destroy();
		$Users->parent::close();
		exit();
	}