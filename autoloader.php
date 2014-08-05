<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/4/14
	 * Time: 9:10 PM
	 */

	require_once("requires/Globals.php");

	function __autoload($class_name) {
		if($class_name == "Utilities"){
			$path = "requires/utils/$class_name.php";
		} else {
			$path = "requires/$class_name.php";
		}
		require_once($path);
	}