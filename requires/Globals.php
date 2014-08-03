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

	$Utilities->checkFile('Db.php', __FILE__, __LINE__);
	require_once("Db.php");
	$DB = new Db();

	$Utilities->checkFile('Courses.php', __FILE__, __LINE__);
	require_once('Courses.php');
	$Courses = new Courses($DB);

	$Utilities->checkFile('Users.php', __FILE__, __LINE__);
	require_once('Users.php');
	$Users = new Users($DB);

	$Utilities->checkFile('Tests.php', __FILE__, __LINE__);
	require_once('Tests.php');
	$Tests = new Tests($DB);