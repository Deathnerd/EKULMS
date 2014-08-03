<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/2/14
	 * Time: 4:58 PM
	 */
	error_reporting(E_ALL);

	//begin global variables definitions
	/**
	 * Returns the root domain. For example, if the script is www.example.com/foo/bar.php,
	 * it will return www.example.com
	 */
//	define('SITE_ROOT', $_SERVER['HTTP_HOST']);

	/**
	 * Set the location of the requires and utilities script. First checks if running on a Windows platform
	 * to account for the special forwardslash (\) directory separator that Windows uses instead of the
	 * globally agreed upon backslash (/) separator that any sane person would use
	 */
	if (strpos(strtolower(php_uname('s')), 'windows') != -1) {
		define('REQUIRES_LOC', $_SERVER['DOCUMENT_ROOT'] . "\\requires");
		define('UTILITIES_LOC', REQUIRES_LOC . "\\utils");
	} else {
		define('REQUIRES_LOC', $_SERVER['DOCUMENT_ROOT'] . "/requires");
		define('UTILITIES_LOC', REQUIRES_LOC . "/utils");
	}

	//begin initialization of classes
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