<?php
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 4/8/14
 * Time: 12:23 AM
 */
	if (!is_file(realpath(dirname(__FILE__)) . '/Courses.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Courses.php! Check your installation");
	}
	require(realpath(dirname(__FILE__)) . "/Courses.php");

class Tests extends Courses{
	protected $connection;

	/**
	 * Constructor!
	 * @uses Courses::__construct()
	 */
	function __construct() {
		parent::__construct(); //call the parent constructor
		$this->connection = Db::connection(); //MySQL connection handler
	}

	public function makeTest($data){
		//check if data passed is an array
		if(!is_array($data) || count($data) == 0){
			trigger_error("Argument for Tests::makeTest must be an array", E_USER_ERROR);
			return false;
		}

		//add quiz name to the tests table
	}
} 