<?
	if(!is_file(realpath(dirname(__FILE__)).'/Db.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Db.php! Check your installation");
	}
	require(realpath(dirname(__FILE__))."/Db.php");

	/**
	* This file manages all things related to courses
	* @uses Db::__construct()
	* @uses Users::__construct()
	*/
	class Courses extends Users {

		/**
		* Constructor!
		* @uses Users::__construct()
		*/
		function __construct(){
			parent::__construct();//call the parent constructor
			$this->connection = parent::connection();//MySQL connection handler
		}

		/**
		* This returns all courses in the database
		* @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		*/
		function fetchAll(){
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table`") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));
			
			if($sql === NULL || $sql === false){
				return false;
			}
			//return all results as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		* This function returns a course according to its ID. Dies if no arguments are supplied or if an SQL error is encountered
		* @param string $id The course id being searched for
		* @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		*/
		function fetchById($id){
			if(!$this->checkString($id)){
				trigger_error("Argument for Courses::fetchCourseById must be a string", E_USER_ERROR);
				return;
			}
			if(func_num_args() < 1){
				trigger_error("Courses::fetchCourseById requires at least one argument".func_num_args()." arguments supplied", E_USER_ERROR);
				return;
			}
			//lowercase and sanitize input
			$courseName = mysqli_real_escape_string($this->connection, strtolower($id));
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseId='$id'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			if($sql === NULL || $sql === false){
				return false;
			}
			//return all results as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		* This function returns a course according to its course number
		* @param string $courseNumber The course number being searched 
		* @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		*/
		function fetchByNumber($courseNumber){
			if(!$this->checkString($courseNumber)){
				trigger_error("Argument for Courses::fetchByNumber must be a string", E_USER_ERROR);
				return;
			}
			if(func_num_args() < 1){
				trigger_error("Courses::fetchByNumber requires at least one argument".func_num_args()." arguments supplied", E_USER_ERROR);
				return;
			}
			//lowercase and sanitize input
			$courseName = mysqli_real_escape_string($this->connection, strtolower($courseNumber));
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseNumber='$courseNumber'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			if($sql === NULL || $sql === false){
				return false;
			}
			//return all results as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}
	}