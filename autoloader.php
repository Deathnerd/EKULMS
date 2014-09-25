<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/4/14
	 * Time: 9:10 PM
	 */

	require_once("vendor/autoload.php");
	use DebugBar\StandardDebugBar;
	$DebugBar = new StandardDebugBar();
	require_once("requires/Globals.php");

	spl_autoload_register(function ($class_name) {
		if ($class_name == "PHPMailer") {
			$path = "requires/phpmailer/class.phpmailer.php";
		} else {
			$path = "requires/$class_name.php";
		}
		require_once($path);
	});

	$DB = new Db;
	$Utils = new Utilities($DB);
