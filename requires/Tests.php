<<<<<<< HEAD
<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/8/14
	 * Time: 12:23 AM
	 */
	if (!is_file(realpath(dirname(__FILE__)) . '/Courses.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Courses.php! Check your installation");
	}
	require_once(realpath(dirname(__FILE__)) . "/Courses.php");

	/**
	 * This class is responsible for the management of Tests
	 */
	class Tests extends Courses {
		protected $connection;

		/**
		 * Constructor!
		 * @uses Courses::__construct()
		 */
		function __construct() {
			parent::__construct(); //call the parent constructor
		}

		/**
		 * @param string $table    The table to add to
		 * @param string $courseId The id of the course to add
		 * @param string $testName The name of the test to add
		 *
		 * @return array
		 */
		private function addToTestTable($table, $courseId, $testName) {
			$this->checkArgumentType($table, __CLASS__, __FUNCTION__, 'string');
			$this->checkArgumentType($courseId, __CLASS__, __FUNCTION__, 'string');
			$this->checkArgumentType($testName, __CLASS__, __FUNCTION__, 'string');
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 3, func_num_args(), true);

			$sql = "SELECT testNumber FROM `$table` WHERE courseId='$courseId';"; //check if the course already has tests in the database
			$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (mysqli_num_rows($query) === 0) { //if the course is not listed in the Tests table, add the first record
				$sql = "INSERT INTO `$table` (courseId, testNumber, testName) VALUES ('$courseId', 1, '$testName');";
			} else { //if the course is listed in the Tests table, increment the test number and insert
				$currentTestNumber = max($this->fetchAllRows($query)); //get the maximum test number
				$currentTestNumber = intval($currentTestNumber['testNumber']);
				$currentTestNumber++; //increment the testId

				//insert the test name, courseId, and new test number into the Tests table
				$sql = "INSERT INTO `$table` (courseId, testNumber, testName) VALUES ('$courseId', $currentTestNumber, '$testName');";
			}
			$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
			if (!$this->checkResult($query)) {
				return false;
			}

			return $currentTestNumber;
		}

		/**
		 * This function will make a test if it does not exist, or update a current test if it exists
		 *
		 * @param $data array Takes in an array of the test
		 *
		 * @return bool True if successful, false if it fails
		 */

		public function makeTest(array $data) {
			//check if data passed is an array
			if (count($data) == 0 || !$this->checkArgumentType($data, __CLASS__, __FUNCTION__, 'array')) {
				return false;
			}
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_num_args(), true);

			$table = $this->tables['Tests'];
			$courseId = mysqli_real_escape_string($this->connection, $data['courseId']);
			$testName = mysqli_real_escape_string($this->connection, $data['_quizName']); //get the new test name

			//add quiz name and relevant course to the tests table
			$currentTestNumber = $this->addToTestTable($table, $courseId, $testName, $data);
			$currentTestNumber = ($currentTestNumber === null) ? '0' : $currentTestNumber;
			//insert the test into the Questions table. Oh boy...
			$questions = $data["quiz"]["questions"];
			$numberOfQuestions = 1;
			$table = $this->tables['Questions'];
			foreach ($questions as $question) {
				$prompt = mysqli_real_escape_string($this->connection, $question['prompt']);
				for ($i = 0; $i < count($question['choices']); $i++) {
					$question['choices'][$i]['value'] = mysqli_real_escape_string($this->connection, $question['choices'][$i]['value']);
				}
				$choices = $question['choices'];
				if ($choices[0] === null) {
					unset($choices[0]);
					$choices = array_values($choices);
				}
				$choiceValues = array(0 => null, 1 => null, 2 => null, 3 => null);
				//cycle through the choices
				$correct = ' ';
				for ($i = 0; $i < count($choices); $i++) {
					$choiceValues[$i] = $choices[$i]['value'];
					//get a
					if ($choices[$i]['correct']) {
						switch ($i) {
							case 0:
							{
								$correct = 'a';
								break;
							}
							case 1:
							{
								$correct = 'b';
								break;
							}
							case 2:
							{
								$correct = 'c';
								break;
							}
							case 3:
							{
								$correct = 'd';
								break;
							}
						}
					}
				}

				//insert everything into the current test
				$sql = "INSERT INTO `$table` (testId, questionNumber, a, b, c, d, correct, prompt) VALUES ($currentTestNumber, $numberOfQuestions, '$choiceValues[0]', '$choiceValues[1]', '$choiceValues[2]', '$choiceValues[3]', '$correct', '$prompt');";
				mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
				$numberOfQuestions++;
			}

			return true;
		}

		/**
		 * This will update a test with new data
		 *
		 * @param $data array Takes in an array of the test
		 *
		 * @return bool Returns true if successful, false if otherwise. Will fail with an error if input is incorrect
		 */
		public function updateTest(array $data) {
			$this->checkArgumentType($data, __CLASS__, __FUNCTION__, 'array');
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_num_args(), true);

			$table = $this->tables['Tests'];
			$quizName = mysqli_real_escape_string($this->connection, $data['_quizName']);
			//check if the test exists
			$sql = "SELECT testName FROM `$table` WHERE testName='$quizName';";
			$results = mysqli_query($this->connection, $sql);
			$currentQuizName = mysqli_fetch_array($results);
			$currentQuizName = $currentQuizName['testName'];
			if (mysqli_num_rows($results) > 0 && $quizName != $currentQuizName) {
				//update the name of the quiz
				$sql = "UPDATE `$table` SET testName='$quizName' WHERE testName='$currentQuizName';";
				$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

				return true;
			}
			//add quiz name and relevant course to the tests table
			$courseId = $data['courseId'];
			//get the max test number
			$sql = "SELECT testNumber FROM `$table` WHERE courseId='$courseId';";
			$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (mysqli_num_rows($query) === 0) {
				return false;
			}

			$currentTestId = max($this->fetchAllRows($query));
			$currentTestId = intval($currentTestId['testNumber']);
			$currentTestId++; //increment the testId
			$questions = $data["quiz"]["questions"];

			//insert the test name, courseId, and new test number into the
			$sql = "INSERT INTO `$table` (courseId, testNumber, testName) VALUES ('$courseId', $currentTestId, '$quizName');";
			$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			return $this->checkResult($query);
		}

		public function fetchByName($name) {
			$this->checkString($name, __CLASS__, __FUNCTION__);
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_num_args(), true);

//			$test = array("_quizName" => $name);
			$name = mysqli_real_escape_string($this->connection, $name); //sanitize input
			$table = $this->tables['Tests'];
			$sql = "SELECT * FROM `$table` WHERE testName='$name';";
			$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
			if (mysqli_num_rows($query) === 0) {
				return false;
			}
			//contains testId, testName, testNumber, courseId
			$testMetadata = mysqli_fetch_array($query);

			//get all questions from the Questions table with the same testId
			$testId = $testMetadata['testId'];
			$table = $this->tables['Questions'];
			$sql = "SELECT * FROM `$table` WHERE testId='$testId' ORDER BY questionNumber;";
			$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (!$this->checkResult($query)) {
				return false;
			}

			$questions = array();
			while ($row = $query->fetch_assoc()) {
				$questions[] = $row;
			}
			//build the array
			$q['questions'] = array();
			for ($i = 0; $i < count($questions); $i++) {
				$q['questions'][$i]["prompt"] = $questions[$i]['prompt']; //add the prompt
				$q['questions'][$i]["choices"] = array(); //create the choices array
				$correct = $questions[$i]['correct'];
				//push choices onto question
				$choices = array(
					0 => $questions[$i]['a'],
					1 => $questions[$i]['b'],
					2 => $questions[$i]['c'],
					3 => $questions[$i]['d']);
				for ($j = 0; $j < count($choices); $j++) {
					if ($choices[$j] === null) { //no more new questions
						break;
					}
					$q['questions'][$i]["choices"][$j]['value'] = $choices[$j];
					$q['questions'][$i]["choices"][$j]['correct'] = (bool)false;
				}
				//apply the correct value
				switch ($correct) {
					case 'a':
					{
						$q['questions'][$i]["choices"][0]['correct'] = (bool)true;
						break;
					}
					case 'b':
					{
						$q['questions'][$i]["choices"][1]['correct'] = (bool)true;
						break;
					}
					case 'c':
					{
						$q['questions'][$i]["choices"][2]['correct'] = (bool)true;
						break;
					}
					case 'd':
					{
						$q['questions'][$i]["choices"][3]['correct'] = (bool)true;
						break;
					}
				}
			}
			//append to the entire test
			$test['_quizName'] = $name;
			$test['quiz'] = $q;

			return $test;
		}

		/**
		 * Fetches all tests in the test table
		 * @return array|bool Returns false if failed, otherwise returns an array of results
		 */
		public function fetchAll() {
			$table = $this->tables['Tests'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table`") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (!$this->checkResult($sql)) {
				return false;
			}

			//return all results as an array
			return $this->fetchAllRows($sql);
		}
=======
<?
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/8/14
	 * Time: 12:23 AM
	 */

	/**
	 * This class is responsible for the management of Tests
	 */
	class Tests{

		/**
		 * Constructor!
		 */
		function __construct(Db $db) {
			$this->Db = $db;
		}

		/**
		 * @param string $courseId The id of the course to add
		 * @param string $testName The name of the test to add
		 *
		 * @return array
		 */
		public function addToTestTable($courseId, $testName) {
			$DB = $this->Db;
			$DB->checkArgumentType($courseId, 'string', __CLASS__, __FUNCTION__);
			$DB->checkArgumentType($testName, 'string', __CLASS__, __FUNCTION__);
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);

			$courseId = $DB->escapeString($courseId);
			$testName = $DB->escapeString($testName);

			$table = $DB->tables['Tests'];
			$currentTestNumber = 1;

			$result = $DB->queryOrDie("SELECT testNumber FROM `$table` WHERE courseId='$courseId';", __FILE__, __LINE__);

			if (!$DB->checkResult($result)) { //if the course is not listed in the Tests table, add the first record
				$sql = "INSERT INTO `$table` (courseId, testNumber, testName) VALUES ('$courseId', 1, '$testName');";
			} else { //if the course is listed in the Tests table, increment the test number and insert
				$currentTestNumber = $this->_getMaxTestNumberForCourse($courseId);
				$currentTestNumber++;

				//insert the test name, courseId, and new test number into the Tests table
				$sql = "INSERT INTO `$table` (courseId, testNumber, testName) VALUES ('$courseId', $currentTestNumber, '$testName');";
			}

			if (!$DB->checkResult($DB->queryOrDie($sql, __FILE__, __LINE__))) {
				return false;
			}

			return $currentTestNumber;
		}

		/**
		 * This function will make a test if it does not exist, or update a current test if it exists
		 *
		 * @param $data array Takes in an array of the test
		 *
		 * @return bool True if successful, false if it fails
		 */

		public function makeTest(array $data) {
			$DB = $this->Db;
			//check if data passed is an array
			$DB->checkArgumentType($data, 'array', __CLASS__, __FUNCTION__);
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);

			$courseId = $DB->escapeString($data['courseId']);
			$testName = $DB->escapeString($data['_quizName']); //get the new test name

			//add quiz name and relevant course to the tests table
			$currentTestNumber = $this->addToTestTable($courseId, $testName, $data);
			$currentTestNumber = ($currentTestNumber === null) ? '0' : $currentTestNumber;
			//insert the test into the Questions table. Oh boy...
			$questions = $data["quiz"]["questions"];
			$numberOfQuestions = 1;
			$table = $DB->tables['Questions'];
			foreach ($questions as $question) {
				$prompt = $DB->escapeString($question['prompt']);
				for ($i = 0; $i < count($question['choices']); $i++) {
					$question['choices'][$i]['value'] = $DB->escapeString($question['choices'][$i]['value']);
				}
				$choices = $question['choices'];
				if ($choices[0] === null) {
					unset($choices[0]);
					$choices = array_values($choices);
				}
				$choiceValues = array(0 => null, 1 => null, 2 => null, 3 => null);
				//cycle through the choices
				$correct = ' ';
				for ($i = 0; $i < count($choices); $i++) {
					$choiceValues[$i] = $choices[$i]['value'];
					//get a
					if ($choices[$i]['correct']) {
						switch ($i) {
							case 0:
							{
								$correct = 'a';
								break;
							}
							case 1:
							{
								$correct = 'b';
								break;
							}
							case 2:
							{
								$correct = 'c';
								break;
							}
							case 3:
							{
								$correct = 'd';
								break;
							}
						}
					}
				}

				//insert everything into the current test
				$sql = "INSERT INTO `$table` (testId, questionNumber, a, b, c, d, correct, prompt) VALUES ($currentTestNumber, $numberOfQuestions, '$choiceValues[0]', '$choiceValues[1]', '$choiceValues[2]', '$choiceValues[3]', '$correct', '$prompt');";
				$DB->queryOrDie($sql, __FILE__, __LINE__);
				$numberOfQuestions++;
			}

			return true;
		}

		/**
		 * This will update a test with new data
		 *
		 * @param $data array Takes in an array of the test
		 *
		 * @return bool Returns true if successful, false if otherwise. Will fail with an error if input is incorrect
		 */
		public function updateTest(array $data) {
			$DB = $this->Db;
			$DB->checkArgumentType($data, 'array', __CLASS__, __FUNCTION__);
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);

			$table = $DB->tables['Tests'];
			$testName = $DB->escapeString($data['_quizName']);

			//check if the test exists
			$results = $DB->queryOrDie("SELECT testName, testId FROM `$table` WHERE testName='$testName';", __FILE__, __LINE__);
			if (!$results) {
				return false;
			}

			$currentQuizName = mysqli_fetch_array($results);
			$testId = $currentQuizName['testId'];
			$currentQuizName = $currentQuizName['testName'];

			if ($testName != $currentQuizName) { //if the new name is different from the old one
				//update the name of the quiz
				$sql = "UPDATE `$table` SET testName='$testName' WHERE testName='$currentQuizName';";
				if (!$DB->queryOrDie($sql, __FILE__, __LINE__)) {
					return false;
				}
			}

			$questions = $data["quiz"]["questions"];
			$numberOfQuestions = 1;
			$table = $DB->tables['Questions'];
			foreach ($questions as $question) {
				$prompt = $DB->escapeString($question['prompt']);
				for ($i = 0; $i < count($question['choices']); $i++) {
					$question['choices'][$i]['value'] = $DB->escapeString($question['choices'][$i]['value']);
				}
				$choices = $question['choices'];
				if ($choices[0] === null) {
					unset($choices[0]);
					$choices = array_values($choices);
				}
				$choiceValues = array(0 => null, 1 => null, 2 => null, 3 => null);
				//cycle through the choices
				$correct = ' ';
				for ($i = 0; $i < count($choices); $i++) {
					$choiceValues[$i] = $choices[$i]['value'];
					//get a
					if ($choices[$i]['correct']) {
						switch ($i) {
							case 0:
							{
								$correct = 'a';
								break;
							}
							case 1:
							{
								$correct = 'b';
								break;
							}
							case 2:
							{
								$correct = 'c';
								break;
							}
							case 3:
							{
								$correct = 'd';
								break;
							}
						}
					}
				}

				//update the current test item
				$sql = "UPDATE `$table` SET a='$choiceValues[0]', b='$choiceValues[1]', c='$choiceValues[2]', d='$choiceValues[3]', correct='$correct', prompt='$prompt' WHERE testId=$testId AND questionNumber=$numberOfQuestions;";
				$DB->queryOrDie($sql, __FILE__, __LINE__);
				$numberOfQuestions++;
			}

			return true;
		}


		/**
		 * This function returns all columns of the
		 *
		 * @param string $name The name of the test to fetch
		 *
		 * @return array|bool Return all columns from Tests table if successful or false if not
		 */
		public function fetchByName($name) {
			$DB = $this->Db;
			$DB->checkString($name, __CLASS__, __FUNCTION__);
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);

			$test = array("_quizName" => $name);
			$name = $DB->escapeString($name); //sanitize input
			$table = $DB->tables['Tests'];

			if (!$query = $DB->queryOrDie("SELECT * FROM `$table` WHERE testName='$name';", __FILE__, __LINE__)) {
				return false;
			}
			//contains testId, testName, testNumber, courseId
			$testMetadata = mysqli_fetch_array($query);

			//stuff to construct the array to return
			$questionSkeleton = array(
				"prompt"  => null,
				"choices" => array()
			);

			//get all questions from the Questions table with the same testId
			$testId = $testMetadata['testId'];
			$table = $DB->tables['Questions'];
			$query = $DB->queryOrDie("SELECT * FROM `$table` WHERE testId='$testId' ORDER BY questionNumber;", __FILE__, __LINE__);

			if (!$DB->checkResult($query)) {
				return false;
			}

			$questions = array();
			while ($row = $query->fetch_assoc()) {
				$questions[] = $row;
			}

			//build the array
			$q['questions'] = array();
			for ($i = 0; $i < count($questions); $i++) {
				array_push($q, $questionSkeleton);
				$q['questions'][$i]["prompt"] = $questions[$i]['prompt']; //add the prompt
				$q['questions'][$i]["choices"] = array(); //create the choices array
				$correct = $questions[$i]['correct'];
				//push choices onto question
				$choices = array(
					0 => $questions[$i]['a'],
					1 => $questions[$i]['b'],
					2 => $questions[$i]['c'],
					3 => $questions[$i]['d']);
				for ($j = 0; $j < count($choices); $j++) {
					if ($choices[$j] === null) { //no more new questions
						break;
					}
					$q['questions'][$i]["choices"][$j]['value'] = $choices[$j];
					$q['questions'][$i]["choices"][$j]['correct'] = (bool)false;
				}
				//apply the correct value
				switch ($correct) {
					case 'a':
					{
						$q['questions'][$i]["choices"][0]['correct'] = (bool)true;
						break;
					}
					case 'b':
					{
						$q['questions'][$i]["choices"][1]['correct'] = (bool)true;
						break;
					}
					case 'c':
					{
						$q['questions'][$i]["choices"][2]['correct'] = (bool)true;
						break;
					}
					case 'd':
					{
						$q['questions'][$i]["choices"][3]['correct'] = (bool)true;
						break;
					}
				}
			}
			//append to the entire test
			$test['quiz'] = $q;

			return $test;
		}

		/**
		 * Fetches all tests in the test table
		 * @return array|bool Returns false if failed, otherwise returns an array of results
		 */
		public function fetchAll() {
			$DB = $this->Db;
			$table = $DB->tables['Tests'];
			$sql = $DB->queryOrDie("SELECT * FROM `$table`", __FILE__, __LINE__);
			if (!$DB->checkResult($sql)) {
				return false;
			}

			//return all results as an array
			return $DB->fetchAllRows($sql);
		}

		/**
		 * Checks if a test exists by name
		 *
		 * @param string $testName The name of the test to check
		 *
		 * @return bool
		 */
		public function testExists($testName) {
			$DB = $this->Db;
			$DB->checkString($testName, __CLASS__, __FUNCTION__);
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__);

			$table = $DB->tables['Tests'];
			$testName = $DB->escapeString($testName);

			return $DB->checkResult($DB->queryOrDie("SELECT EXISTS (SELECT 1 FROM `$table` WHERE testName = '$testName');", __FILE__, __LINE__));
		}

		/**
		 * Gets the maximum test number for a course
		 *
		 * @param string $course_id The course id to look for
		 *
		 * @return int The test number if there is one or 0 (false) if not
		 */
		private function _getMaxTestNumberForCourse($course_id){
			$DB = $this->Db;
			$DB->checkString($course_id, __CLASS__, __FUNCTION__);
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);

			$table = $DB->tables['Tests'];
			$course_id = $DB->escapeString($course_id);
			$result = $DB->queryOrDie("SELECT MAX(testNumber) AS 'testNumber' FROM `$table` WHERE courseId='$course_id'; ", __FILE__, __LINE__);
			if($DB->checkResult($result)){
				return intval($result['testNumber']);
			}
			return 0;
		}

		public function submitResults($userId, $data){
			return true;
		}
>>>>>>> Tests
	}