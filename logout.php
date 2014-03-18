<?
	/**
	* This page kills a user session
	*/
	session_start();
	unset($_SESSION['userName']);
	unset($_SESSION['admin']);
	session_destroy();
	header('Location: index.php');