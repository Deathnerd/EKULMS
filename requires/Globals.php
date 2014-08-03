<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/2/14
	 * Time: 4:58 PM
	 */
	error_reporting(E_ALL);

	require_once("utils/utilities.php");
	$Utilities = new Utilities();

	require_once("Db.php");
	$DB = new Db();

	require_once('Courses.php');
	$Courses = new Courses($DB);

	require_once('Users.php');
	$Users = new Users($DB);

	require_once('Tests.php');
	$Tests = new Tests($DB);