<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/2/14
	 * Time: 6:37 PM
	 */

	session_start();
	if (!is_file('requires/Users.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Users.php! Check your installation");
	}
	require_once('requires/Users.php'); //import the user database functions

	if ($_SESSION['userName'] != '') { //if a user is already signed in
		header('Location: index.php');
		exit();
	}