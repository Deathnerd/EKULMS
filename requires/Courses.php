<?
	if(!is_file(realpath(dirname(__FILE__)).'/Users.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Users.php! Check your installation");
	}
	require(realpath(dirname(__FILE__))."/Users.php");

	/**
	* This file manages all things related to courses
	* @uses Db::__construct()
	* @uses Users::__construct()
	* @todo make a function to check if a course already exists
	*/
	class Courses extends Users {

		/**
		* Constructor!
		* @uses Users::__construct()
		*/
		function __construct(){
			parent::__construct();//call the parent constructor
			$this->connection = Db::connection();//MySQL connection handler
		}

		/**
		* This returns all courses in the database
		* @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		*/
		function fetchAll(){
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table`") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));
			echo mysqli_info($this->connection);
			if($sql === NULL || $sql === false){
				return false;
			}
			$rows = array();
			while ($row = $sql->fetch_assoc()){
				$rows[]=$row;
			}
			//return all results as an array
			return $rows;
		}

		/**
		* This function returns a course according to its ID. Dies if no arguments are supplied or if an SQL error is encountered
		* @param string $id The course id being searched for
		* @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		*/
		function fetchById($id){
			$table = $this->tables['Courses'];
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
			$sql = mysqli_query($this->connection, "SELECT * FROM `Courses` WHERE courseId='$id'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			if($sql === NULL || $sql === false){
				return false;
			}
			//return all results as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		* This function returns a course according to its course name
		* @param string $courseName The course name being searched 
		* @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		*/
		function fetchByName($courseName){
			if(func_num_args() < 1){
				trigger_error("Courses::fetchByName requires at least one argument".func_num_args()." arguments supplied", E_USER_ERROR);
				return;
			}
			if(!$this->checkString($courseName)){
				trigger_error("Argument for Courses::fetchByName must be a string", E_USER_ERROR);
				return;
			}
			//lowercase and sanitize input
			$courseName = mysqli_real_escape_string($this->connection, strtolower($courseName));
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseName='$courseName'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			if($sql === NULL || $sql === false){
				return false;
			}
			//return all results as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		* This function will create a course. Dies on an sql error
		* @param string $courseName
		* @param string $id
		* @param string $description
		* @return boolean Return true if successful, or false if not
		*/
		function create($courseName, $id, $description){
			if(func_num_args() < 3){
				trigger_error("Courses::create requires three arguments. ".func_num_args()." arguments supplied", E_USER_ERROR);
				return;
			}
			if(!$this->checkString(func_get_args())){
				trigger_error("Arguments for Courses::create must be a string", E_USER_ERROR);
				return;
			}
			//lowercase and sanitize input
			$table = $this->tables['Courses'];
			$courseName = mysqli_real_escape_string($this->connection, $courseName);
			$id = mysqli_real_escape_string($this->connection, $id);
			$description = mysqli_real_escape_string($this->connection, $description);

			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (courseName, courseId, description) VALUES ('$courseName', '$id', '$description')") or die("Error in file ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));
			//check if successfully entered
			if($this->fetchById($id) === false || $sql == false || $sql === null){
				return false;
			}
			//success
			return true;
		}

		/**
		* This function will change a selected column of the Courses table for a selected course id
		* @param string $id The id of the course to change
		* @param string $column The column whose value will be changed
		* @param string $value The value that will be inserted
		* @return boolean Returns true if successful, otherwise false
		*/

		function modify($id, $column, $value){
			if(func_num_args() < 3){
				trigger_error("Courses::modify requires three arguments. ".func_num_args()." arguments supplied", E_USER_ERROR);
				return;
			}
			if(!$this->checkString(func_get_args())){
				trigger_error("Arguments for Courses::modify must be a string", E_USER_ERROR);
				return;
			}

			$id = mysqli_real_escape_string($this->connection, $id);
			$column = mysqli_real_escape_string($this->connetion, $column);
			if($value == "description"){ //don't lowercase the value if it's a description
				$value = mysqli_real_escape_string($this->connection, $value);
			} else {
				$value == mysqli_real_escape_string($this->connection, strtolower($value));
			}

			$sql = mysqli_query($this->connection, "UPDATE `Courses` SET $column='$value' WHERE courseId='$id'") or die("Error in file ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));
			//if the course id was changed, then update the $id to the changed id
			if($column == "courseId"){
				$id = $value;
			}
			//check if the row exists
			if($this->fetchById($id) === false || $sql == false || $sql === null){
				$changedValue = $this->fetchById($id);
				if($changedValue[$column] != $value){ //check if the value was not actually changed
					return false;
				}
			}
			return true;
		}
	}