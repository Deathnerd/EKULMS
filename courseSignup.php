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

	if (!isset($_SESSION["userName"])) { //if a user isn't signed in
		header('Location: index.php'); //redirect to index
		exit();
	}
	//print the header
	require('requires/header.php');
