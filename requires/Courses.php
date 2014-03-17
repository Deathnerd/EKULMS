<?
	if(!is_file(realpath(dirname(__FILE__)).'/Db.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Db.php! Check your installation");
	}
	require(realpath(dirname(__FILE__))."/Db.php");

	//contains methods for manipulating courses
	class Courses extends Db {

		function __construct(){
			parent::__construct()//call the parent constructor
			$this->connection = parent::connection()//MySQL connection handler
		}
	}
 