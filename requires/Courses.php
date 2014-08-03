<?

	/**
	 * This file manages all things related to courses
	 */
	class Courses {

		/**
		 * Constructor!
		 */
		function __construct(Db $db) {
			$this->Db = $db;
		}

		/**
		 * This returns all courses in the database
		 *
		 * @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		 */
		public function fetchAll() {
			$DB = $this->Db;
			$table = $DB->tables['Courses'];
			$sql = $DB->queryOrDie("SELECT * FROM `$table`", __FILE__, __LINE__);

			if (!$DB->checkResult($sql)) {
				return false;
			}

			//return all results as an array
			return $DB->fetchAllRows($sql);
		}

		/**
		 * This function returns a course according to its ID. Dies if no arguments are supplied or if an SQL error is encountered
		 *
		 * @param string $courseId The course id being searched for
		 *
		 * @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		 */
		public function fetchById($courseId) {
			$DB = $this->Db;
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);
			//lowercase and sanitize input
			$courseId = $DB->escapeString(strtolower($courseId));
			$table = $DB->tables['Courses'];
			$sql = $DB->queryOrDie("SELECT * FROM `$table` WHERE courseId='$courseId'", __FILE__, __LINE__);

			if (!$DB->checkResult($sql)) {
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
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			//lowercase and sanitize input
			$courseName = $DB->escapeString(strtolower($courseName));
			$table = $DB->tables['Courses'];
			$sql = $DB->queryOrDie("SELECT * FROM `$table` WHERE courseName='$courseName'", __FILE__, __LINE__);

			if (!$DB->checkResult($sql)) {
				return false;
			}

			//return all results as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		 * This function will create a course. Dies on an sql error
		 *
		 * @param string $courseName The full name of the course
		 * @param string $courseId   The course id
		 * @param string $description
		 *
		 * @return boolean Return true if successful, or false if not
		 */
		public function create($courseName, $courseId, $description) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 3, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			//lowercase and sanitize input
			$table = $DB->tables['Courses'];
			$courseName = $DB->escapeString($courseName);
			$courseId = $DB->escapeString($courseId);
			$description = $DB->escapeString($description);

			$sql = $DB->queryOrDie("INSERT INTO `$table` (courseName, courseId, description) VALUES ('$courseName', '$courseId', '$description')", __FILE__, __LINE__);

			//check if successfully entered
			return $this->fetchById($courseId) && $DB->checkResult($sql);
		}

		/**
		 * This function will change a selected column of the Courses table for a selected course id
		 *
		 * @param string $courseId The id of the course to change
		 * @param string $column   The column whose value will be changed
		 * @param string $value    The value that will be inserted
		 *
		 * @return boolean Returns true if successful, otherwise false
		 */

		public function modify($courseId, $column, $value) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 3, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			$courseId = $DB->escapeString($courseId);
			$column = $DB->escapeString($column);
			$value = $DB->escapeString($value);

			$table = $DB->tables['Courses'];
			$sql =  $DB->queryOrDie("UPDATE `$table` SET $column='$value' WHERE courseId='$courseId'", __FILE__, __LINE__);
			//if the course id was changed, then update the $id to the changed id
			if ($column == "courseId") {
				$courseId = $value;
			}
			//check if the row exists
			if (!$this->fetchById($courseId) || !$DB->checkResult($sql)) {
				$changedValue = $this->fetchById($courseId);
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
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			$courseId = $DB->escapeString($courseId);
			$userName = $DB->escapeString(strtolower($userName));

			require_once("Users.php");

			$Users = new Users($DB);
			//get the userId from the Users table
			$userId = $Users->fetchUser($userName);
			if(!$userId){
				return false;
			}
			$userId = intval($userId['id']);
			//add instructor to the Teach table
			$table = $DB->tables['Teach'];
			$sql =  $DB->queryOrDie("INSERT INTO `$table` (id, courseNumber) VALUES ($userId, '$courseId')", __FILE__, __LINE__);

			return $DB->checkResult($sql);
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
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			$courseId = $DB->escapeString($courseId);
			$userName = $DB->escapeString(strtolower($userName));

			require_once("Users.php");
			$Users = new Users($this->Db);
			//get the userId from the Users table
			$userId = $Users->fetchUser($userName);
			if(!$userId){
				return false;
			}
			$userId = intval($userId['id']);
			//add instructor to the Enrollment table
			$table = $DB->tables['Enrollment'];

			if(!$DB->queryOrDie("SELECT id FROM `$table` WHERE courseId = '$courseId' AND id=$userId;", __FILE__, __LINE__)){
				return $DB->checkResult($DB->queryOrDie("INSERT INTO `$table` (id, courseId) VALUES ($userId, '$courseId')", __FILE__, __LINE__));
			}
			return false;
		}

		/**
		 * Checks whether a course exists in the database
		 *
		 * @param $courseId string the id of the course to check
		 *
		 * @returns boolean returns true if course exists, false otherwise
		 */
		public function courseExists($courseId) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, 1);
			$DB->checkString($courseId, __CLASS__, __FUNCTION__);

			$courseId = $DB->escapeString($courseId);
			$table = $DB->tables['Courses'];

			$sql = $DB->queryOrDie("SELECT * FROM `$table` WHERE courseId='$courseId'",__FILE__, __LINE__);

			return $DB->checkResult($sql);
		}

		/**
		 * Fetches all enrolled courses for a student
		 *
		 * @param $userName string the user name to search for
		 *
		 * @param $type string the type of user to fetch
		 *
		 * @return array|bool Return an array of courses if enrolled in any, false if otherwise
		 */
		public function fetchEnrolledCourses($userName, $type) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);
			$DB->checkString($userName, __CLASS__, __FUNCTION__);

			$userName = $DB->escapeString(strtolower($userName));
			$Users = new Users($DB);
			//get the userId from the Users table
			$userId = $Users->fetchUser($userName);
			$userId = $userId['id'];
			if($type === 'student')
				$table = $DB->tables['Enrollment'];
			else
				$table = $DB->tables['Teach'];
			$sql = $DB->queryOrDie("SELECT * FROM `$table` WHERE id=$userId", __FILE__, __LINE__);

			//if the student is not enrolled in any courses
			if (!$DB->checkResult($sql)) {
				return false;
			}

			//get the course ids so we can search and return all courses information the user is enrolled in
			$courseIds = array();
			while ($row = $sql->fetch_assoc()) {
				$courseIds[] = $row['courseId'];
			}

			$table = $DB->tables['Courses'];
			$rows = array();
			//build the return array
			$count = 0;
			foreach ($courseIds as $courseId) {
				$sql = $DB->queryOrDie("SELECT * FROM `$table` WHERE courseId='$courseId'", __FILE__, __LINE__);

				if (!$DB->checkResult($sql)) {
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


		/**
		 * Add a course to the database
		 *
		 * @param string $courseId The courseId
		 * @param string $courseName The course name
		 * @param string $description The description of the course
		 *
		 * @return bool
		 */
		public function addCourse($courseId, $courseName, $description = '') {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 3, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			if (!$this->courseExists($courseId)) {
				return false;
			}

			$table = $DB->tables['Courses'];
			$courseId = $DB->escapeString($courseId);
			$courseName = $DB->escapeString($courseName);
			$description = $DB->escapeString($description);

			if(!$DB->queryOrDie("SELECT * FROM `$table` WHERE courseId = '$courseId' AND courseName='$courseName' AND description = '$description';", __FILE__, __LINE__)){
				return  $DB->checkResult($DB->queryOrDie("INSERT INTO `$table` (courseId, courseName, description) VALUES ($courseId, $courseName, $description);", __FILE__, __LINE__));
			}

			return false;
		}


		/**
		 * This will update the course based on the courseId
		 *
		 * @param string $courseId The id of the course to update
		 * @param string $courseName The name of the course
		 * @param string $description The description of the course
		 *
		 * @return bool
		 */
		public function updateCourse($courseId, $courseName, $description){
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 3, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			if (!$this->courseExists($courseId)) {
				return false;
			}

			$table = $DB->tables['Courses'];
			$courseId = $DB->escapeString($courseId);
			$courseName = $DB->escapeString($courseName);
			$description = $DB->escapeString($description);

			return $DB->checkResult($DB->queryOrDie("UPDATE `$table` SET courseName='$courseName', description='$description' WHERE courseid = '$courseId';", __FILE__, __LINE__ ));
		}
	}