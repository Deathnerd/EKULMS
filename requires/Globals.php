<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/2/14
	 * Time: 4:58 PM
	 */
	error_reporting(E_ALL);
	//check the php version. Requires at least 5.5
	if (version_compare(phpversion(), '5.5', '<')) {
		trigger_error("EKULMS requires at least PHP version 5.5. Please check your installation", E_USER_ERROR);
	}


	//begin global variables definitions
	/**
	 * Returns the root domain. For example, if the script is www.example.com/foo/bar.php,
	 * it will return www.example.com
	 */
	define('SITE_ROOT', $_SERVER['HTTP_HOST']);

	define('SMTP_SERVER', 'smtp.gmail.com');
	define('SMTP_USER', 'wes.gilleland@gmail.com');
	define('SMTP_PORT', 587);
	define('SMTP_PASSWORD', "LEnTf03wpBopAF2U");

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