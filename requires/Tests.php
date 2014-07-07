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
		 * @param string $table The table to add to
		 * @param string $courseId The id of the course to add
		 * @param string $testName The name of the test to add
		 *
		 * @return array
		 */
		private function addToTestTable($table, $courseId, $testName){
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
			$returnValues = array($currentTestNumber, $sql);
			return $returnValues;
		}

		/**
		 * This function will make a test if it does not exist, or update a current test if it exists
		 *
		 * @param $data array Takes in an array of the test
		 *
		 * @return bool True if successful, false if it fails
		 */

		public function makeTest($data) {
			//check if data passed is an array
			if (!is_array($data) || count($data) == 0) {
				trigger_error("Argument for Tests::makeTest must be an array", E_USER_ERROR);
				return false;
			}
			if (func_num_args() < 1) {
				trigger_error("Tests::makeTest requires at least one argument" . func_num_args() . " arguments supplied", E_USER_ERROR);
				return false;
			}

			$table = $this->tables['Tests'];
			$courseId = mysqli_real_escape_string($this->connection, $data['courseId']);
			$testName = mysqli_real_escape_string($this->connection, $data['_quizName']); //get the new test name

			//add quiz name and relevant course to the tests table
			$vals = $this->addToTestTable($table, $courseId, $testName, $data);
			$sql = $vals[1];
			$currentTestNumber = ($vals[0] === null)? '0' : $vals[0];
			$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
			if (!$this->checkResult($query)) {
				return false;
			}
			//insert the test into the Questions table. Oh boy...
			$questions = $data["quiz"]["questions"];
			$numberOfQuestions = 1;
			$table = $this->tables['Questions'];
			foreach ($questions as $question) {
				$prompt = mysqli_real_escape_string($this->connection, $question['prompt']);
				for($i = 0; $i < count($question['choices']); $i++){
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
		 * @param $data array Takes in an array of the test
		 *
		 * @return bool Returns true if successful, false if otherwise. Will fail with an error if input is incorrect
		 */
		public function updateTest($data) {
			//check if data passed is an array
			if (!is_array($data) || count($data) == 0) {
				trigger_error("Argument for Tests::makeTest must be an array", E_USER_ERROR);
			}
			if (func_num_args() < 1) {
				trigger_error("Tests::makeTest requires at least one argument" . func_num_args() . " arguments supplied", E_USER_ERROR);
			}

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

			if (!$this->checkResult($query)) {
				return false;
			}
			return true;
		}

		public function fetchByName($name) {
			if (!$this->checkString($name)) {
				trigger_error("Argument for Tests::fetchByName must be a string", E_USER_ERROR);

				return false;
			}
			if (func_num_args() != 1) {
				trigger_error("Tests::makeTest requires one argument" . func_num_args() . " arguments supplied", E_USER_ERROR);

				return false;
			}

			$test = array("_quizName" => $name);
			$name = mysqli_real_escape_string($this->connection, $name); //sanitize input
			$table = $this->tables['Tests'];
			$sql = "SELECT * FROM `$table` WHERE testName='$name';";
			$query = mysqli_query($this->connection, $sql) or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));
			if (mysqli_num_rows($query) === 0) {
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
		 * Lists all tests in the test table
		 * @return array|bool Returns false if failed, otherwise returns an array of results
		 */
		public function listAll() {
			$table = $this->tables['Tests'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table`") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (!$this->checkResult($sql)) {
				return false;
			}

			//return all results as an array
			return $this->fetchAllRows($sql);
		}
	}