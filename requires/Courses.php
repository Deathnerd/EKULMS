<?
	if (!is_file(realpath(dirname(__FILE__)) . '/Users.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Users.php! Check your installation");
	}
	require(realpath(dirname(__FILE__)) . "/Users.php");

	/**
	 * This file manages all things related to courses
	 * @uses Db::__construct()
	 * @uses Users::__construct()
	 */


	class Courses extends Users {

		protected $connection = null;

		/**
		 * Constructor!
		 * @uses Users::__construct()
		 */
		function __construct() {
			parent::__construct(); //call the parent constructor
			$this->connection = Db::getConnection(); //MySQL connection handler
		}

		/**
		 * This returns all courses in the database
		 * @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		 */
		public function fetchAll() {
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table`") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (!$this->checkResult($sql)) {
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
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_num_args(), true);
			//lowercase and sanitize input
			$id = mysqli_real_escape_string($this->connection, strtolower($id));
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseId='$id'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (!$this->checkResult($sql)) {
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
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_num_args(), true);
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			//lowercase and sanitize input
			$courseName = mysqli_real_escape_string($this->connection, strtolower($courseName));
			$table = $this->tables['Courses'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseName='$courseName'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (!$this->checkResult($sql)) {
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
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 3, func_num_args(), true);
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			//lowercase and sanitize input
			$table = $this->tables['Courses'];
			$courseName = mysqli_real_escape_string($this->connection, $courseName);
			$id = mysqli_real_escape_string($this->connection, $id);
			$description = mysqli_real_escape_string($this->connection, $description);

			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (courseName, courseId, description) VALUES ('$courseName', '$id', '$description')") or die("Error in file " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			//check if successfully entered
			return $this->fetchById($id) && $this->checkResult($sql);
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
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 3, func_num_args(), true);
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			$id = mysqli_real_escape_string($this->connection, $id);
			$column = mysqli_real_escape_string($this->connection, $column);
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
			if (!$this->fetchById($id) || !$this->checkResult($sql)) {
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
			$this->checkNumberOfArguments(__CLASS__,__FUNCTION__, 2, func_num_args(), true);
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			$courseId = mysqli_real_escape_string($this->connection, $courseId);
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));

			//get the userId from the Users table
			$userId = $this->fetchUser($userName);
			$userId = intval($userId['id']);
			//add instructor to the Teach table
			$table = $this->tables['Teach'];
			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (id, courseNumber) VALUES ($userId, '$courseId')") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			return $this->checkResult($sql);
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
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 2, func_num_args(), true);
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			$courseId = mysqli_real_escape_string($this->connection, $courseId);
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));

			//get the userId from the Users table
			$userId = $this->fetchUser($userName);
			$userId = intval($userId['id']);
			//add instructor to the Enrollment table
			$table = $this->tables['Enrollment'];
			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (id, courseId) VALUES ($userId, '$courseId')") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			return $this->checkResult($sql);
		}

		/**
		 * Checks whether a course exists in the database
		 *
		 * @param $courseId string the id of the course to check
		 *
		 * @returns boolean returns true if course exists, false otherwise
		 */
		public function courseExists($courseId) {
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_num_args(), 1);
			$this->checkString($courseId, __CLASS__, __FUNCTION__);

			$courseId = mysqli_real_escape_string($this->connection, $courseId);
			$table = $this->tables['Courses'];

			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE courseId='$courseId'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			return $this->checkResult($sql);
		}

		/**
		 * Fetches all enrolled courses for a student
		 * @param $userName string the user name to search for
		 *
		 * @return array|bool Return an array of courses if enrolled in any, false if otherwise
		 */
		public function fetchEnrolledCourses($userName){
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_num_args(), true);
			$this->checkString($userName, __CLASS__, __FUNCTION__);

			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$userId = $this->fetchUser($userName);
			$userId = $userId['id'];
			$table = $this->tables['Enrollment'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE id=$userId")  or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			//if the student is not enrolled in any courses
			if (!$this->checkResult($sql)) {
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

				if (!$this->checkResult($sql)) {
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


		public function addCourse($courseId, $courseName, $description = ''){
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 3, func_num_args(), true);
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			if(!$this->courseExists($courseId))
				return false;

			$courseId = mysqli_real_escape_string($this->database, $courseId);
			$courseName = mysqli_real_escape_string($this->database, $courseName);
			$description = mysqli_real_escape_string($this->database, $description);

			$sql = mysqli_query($this->database, "INSERT INTO courses (courseId, courseName, description) VALUES ($courseId, $courseName, $description);") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
			return $this->checkResult($sql);
		}
	}