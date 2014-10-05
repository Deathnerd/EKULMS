<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/2/14
	 * Time: 4:58 PM
	 */
	define('DS', DIRECTORY_SEPARATOR);
	define('SITE_ROOT', (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
	define('REGISTRATION_OPEN', false);
	/*
	 * Load debug file
	 */
	require_once("Debugs.php");
	/*
	 * Parse the error_reporting values
	 */
	$error_ini_file = __DIR__ . DS . "error_reporting.ini";
	if(file_exists($error_ini_file) && false){
		$error_config = parse_ini_file($error_ini_file);
		$error_bitmask = 0;
		if($error_config['E_ALL']){
			//error reporting for everything has been chosen, skip everything else
			$error_bitmask = E_ALL;
		} else {
			for($i = 0; $i < count($error_config['errors']); $i++){
				/*
				 * Bitmask calculations based on http://www.bx.com.au/tools/ultimate-php-error-reporting-wizard
				 */
				//multiply by the true/false of the configuration to include or exclude it from the bitmask
				$error_bitmask += pow(2, $i) * intval($error_config['errors'][$i]);
			}
		}
		//Apply the bitmask
		error_reporting(E_ALL);
	}

	//check the php version. Requires at least 5.5
	if (version_compare(phpversion(), '5.5', '<')) {
		trigger_error("EKULMS requires at least PHP version 5.5. Please check your installation", E_USER_ERROR);
	}


	//begin global variables definitions
	/**
	 * Returns the root domain. For example, if the script is www.example.com/foo/bar.php,
	 * it will return www.example.com
	 */
	define('SMTP_SERVER', 'smtp.server.com');
	define('SMTP_USER', 'user@server.com');
	define('SMTP_PORT', 587);
	define('SMTP_PASSWORD', "password");

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