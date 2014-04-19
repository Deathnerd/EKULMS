<?
	if (!is_file(realpath(dirname(__FILE__)) . '/Users.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Users.php! Check your installation");
	}
	require(realpath(dirname(__FILE__)) . "/Users.php");

	/**
	 * This file manages all things related to courses
	 * @uses Db::__construct()
	 * @uses Users::__construct()
	 * @todo make a function to check if a course already exists
	 */
	class Courses extends Users {

		protected $connection = null;

		/**
		 * Constructor!
		 * @uses Users::__construct()
		 */
		function __construct() {
			parent::__construct(); //call the parent constructor
			$this->connection = Db::connection(); //MySQL connection handler
		}

		/**
		 * This returns all courses in the database
		 * @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		 */
		public function fetchAll() {
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table`") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if ($sql === null || $sql === false || mysqli_num_rows($sql) === 0) {
				return false;
			}

			//return all results as an array
			return $this->fetchAllRows($sql);
		}

		/**
		 * This function returns a course according to its ID. Dies if no arguments are supplied or if an SQL error is encountered
		 *
		 * @param string $id The course id being searched for
		 *
		 * @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		 */
		public function fetchById($id) {
			if (!$this->checkString($id)) {
				trigger_error("Argument for Courses::fetchCourseById must be a string", E_USER_ERROR);
			}
			if (func_num_args() < 1) {
				trigger_error("Courses::fetchCourseById requires at least one argument" . func_num_args() . " arguments supplied", E_USER_ERROR);
			}
			//lowercase and sanitize input
			$id = mysqli_real_escape_string($this->connection, strtolower($id));
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseId='$id'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if ($sql === null || $sql === false || mysqli_num_rows($sql) === 0) {
				return false;
			}

			//return all results as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		 * This function returns a course according to its course name
		 *
		 * @param string $courseName The course name being searched
		 *
		 * @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		 */
		public function fetchByName($courseName) {
			if (func_num_args() < 1) {
				trigger_error("Courses::fetchByName requires at least one argument" . func_num_args() . " arguments supplied", E_USER_ERROR);
			}
			if (!$this->checkString($courseName)) {
				trigger_error("Argument for Courses::fetchByName must be a string", E_USER_ERROR);
			}
			//lowercase and sanitize input
			$courseName = mysqli_real_escape_string($this->connection, strtolower($courseName));
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseName='$courseName'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if ($sql === null || $sql === false || mysqli_num_rows($sql) === 0) {
				return false;
			}

			//return all results as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		 * This function will create a course. Dies on an sql error
		 *
		 * @param string $courseName
		 * @param string $id
		 * @param string $description
		 *
		 * @return boolean Return true if successful, or false if not
		 */
		public function create($courseName, $id, $description) {
			if (func_num_args() < 3) {
				trigger_error("Courses::create requires three arguments. " . func_num_args() . " arguments supplied", E_USER_ERROR);
			}
			if (!$this->checkString(func_get_args())) {
				trigger_error("Arguments for Courses::create must be a string", E_USER_ERROR);
			}
			//lowercase and sanitize input
			$table = $this->tables['Courses'];
			$courseName = mysqli_real_escape_string($this->connection, $courseName);
			$id = mysqli_real_escape_string($this->connection, $id);
			$description = mysqli_real_escape_string($this->connection, $description);

			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (courseName, courseId, description) VALUES ('$courseName', '$id', '$description')") or die("Error in file " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
			//check if successfully entered
			if ($this->fetchById($id) === false || $sql == false || $sql === null || mysqli_num_rows($sql) === 0) {
				return false;
			}

			//success
			return true;
		}

		/**
		 * This function will change a selected column of the Courses table for a selected course id
		 *
		 * @param string $id     The id of the course to change
		 * @param string $column The column whose value will be changed
		 * @param string $value  The value that will be inserted
		 *
		 * @return boolean Returns true if successful, otherwise false
		 */

		public function modify($id, $column, $value) {
			if (func_num_args() < 3) {
				trigger_error("Courses::modify requires three arguments. " . func_num_args() . " arguments supplied", E_USER_ERROR);
			}
			if (!$this->checkString(func_get_args())) {
				trigger_error("Arguments for Courses::modify must be a string", E_USER_ERROR);
			}

			$id = mysqli_real_escape_string($this->connection, $id);
			$column = mysqli_real_escape_string($this->connetion, $column);
			if ($value == "description") { //don't lowercase the value if it's a description
				$value = mysqli_real_escape_string($this->connection, $value);
			} else {
				$value = mysqli_real_escape_string($this->connection, $value);
			}

			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "UPDATE `$table` SET $column='$value' WHERE courseId='$id'") or die("Error in file " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
			//if the course id was changed, then update the $id to the changed id
			if ($column == "courseId") {
				$id = $value;
			}
			//check if the row exists
			if ($this->fetchById($id) === false || $sql == false || $sql === null) {
				$changedValue = $this->fetchById($id);
				if ($changedValue[$column] != $value) { //check if the value was not actually changed
					return false;
				}
			}

			return true;
		}

		/**
		 * This function adds an instructor and assigns them to a course
		 *
		 * @param string $courseId The course id to add the user to
		 * @param string $userName The userName to be associated with the course
		 *
		 * @return boolean Returns true if successful, false if otherwise
		 */

		public function addInstructor($courseId, $userName) {
			if (func_num_args() < 2) {
				trigger_error("Courses::addInstructor requires two arguments. " . func_num_args() . " arguments supplied", E_USER_ERROR);

				return false;
			}
			if (!$this->checkString($courseId) || !$this->checkString($userName)) {
				trigger_error("Arguments for Courses::addInstructor must be a string", E_USER_ERROR);

				return false;
			}
			$courseId = mysqli_real_escape_string($this->connection, $courseId);
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));

			//get the userId from the Users table
			$userId = $this->fetchUser($userName);
			$userId = intval($userId['id']);
			var_dump($userId);
			//add instructor to the Teach table
			$table = $this->tables['Teach'];
			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (id, courseNumber) VALUES ($userId, '$courseId')") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if ($sql == false || $sql === null || mysqli_num_rows($sql) === 0) {
				return false;
			}

			return true;
		}

		/**
		 * Add a student to a course
		 *
		 * @param $courseId string the course to add a student to
		 * @param $userName string the username of the student to add
		 *
		 * @return boolean returns true if successful, false if otherwise
		 */
		public function addStudent($courseId, $userName) {
			if (func_num_args() < 2) {
				trigger_error("Courses::addStudent requires two arguments. " . func_num_args() . " arguments supplied", E_USER_ERROR);

				return false;
			}
			if (!$this->checkString($courseId) || !$this->checkString($userName)) {
				trigger_error("Arguments for Courses::addStudent must be a string", E_USER_ERROR);

				return false;
			}
			$courseId = mysqli_real_escape_string($this->connection, $courseId);
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));

			//get the userId from the Users table
			$userId = $this->fetchUser($userName);
			$userId = intval($userId['id']);
			//add instructor to the Enrollment table
			$table = $this->tables['Enrollment'];
			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (id, courseNumber) VALUES ($userId, '$courseId')") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if ($sql == false || $sql === null || mysqli_num_rows($sql) === 0) {
				return false;
			}

			return true;
		}

		/**
		 * Checks whether a course exists in the database
		 *
		 * @param $courseId string the id of the course to check
		 *
		 * @returns boolean returns true if course exists, false otherwise
		 */
		public function courseExists($courseId) {
			if (func_num_args() < 1) {
				trigger_error("Courses::courseExists requires one argument. " . func_num_args() . " argument supplied", E_USER_ERROR);
			}

			if (!$this->checkString($courseId)) {
				trigger_error("Arguments for Courses::checkUserExists must be a string", E_USER_ERROR);
			}
			$courseId = mysqli_real_escape_string($this->connection, $courseId);
			$table = $this->tables['Courses'];

			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseId='$courseId'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
			if ($sql === false || $sql === null || mysqli_num_rows($sql) === 0) {
				return false;
			}

			return true;
		}

		/**
		 * @param $userName string the user name to search for
		 *
		 * @return array|bool Return an array of courses if enrolled in any, false if otherwise
		 */
		public function fetchEnrolledCourses($userName){
			if (func_num_args() < 1) {
				trigger_error("Courses::fetchEnrolledCourses requires one argument. " . func_num_args() . " argument supplied", E_USER_ERROR);
			}
			if (!$this->checkString($userName)) {
				trigger_error("Arguments for Courses::fetchEnrolledCourses must be a string", E_USER_ERROR);
			}

			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$userId = $this->fetchUser($userName);
			$userId = $userId['id'];
			$table = $this->tables['Enrollment'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE id=$userId")  or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			//if the student is not enrolled in any courses
			if ($sql === false || $sql === null || mysqli_num_rows($sql) === 0) {
				return false;
			}

			//get the course ids so we can search and return all courses information the user is enrolled in
			$courseIds = array();
			while ($row = $sql->fetch_assoc()) {
				$courseIds[] = $row['courseId'];
			}

			$table = $this->tables['Courses'];
			$rows = array();
			//build the return array
			$count = 0;
			foreach($courseIds as $courseId){
				$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseId='$courseId'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

				if ($sql === false || $sql === null || mysqli_num_rows($sql) === 0) {
					return false;
				}
				$result = $sql->fetch_row();
				$rows[$count]['courseId'] = $result[0];
				$rows[$count]['courseName'] = $result[1];
				$rows[$count]['description'] = $result[2];
				$count++;
			}

			//return all results as an array
			return $rows;
		}
	}