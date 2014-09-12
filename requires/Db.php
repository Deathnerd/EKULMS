<?
	/**
	 * Contains the Db class
	 */

	/**
	 * Class for facilitating Database connections
	 * @todo change from ini configuration file to JSON
	 */
	class Db {
		/**
		 * @var string The database name from the config file
		 */
		protected $database;
		/**
		 * @var string The database password from the config file
		 */
		protected $password;
		/**
		 * @var string The database host from the config file
		 */
		protected $host;
		/**
		 * @var string The database user from the config file
		 */
		protected $user;
		/**
		 * @var object The MySQL connection object
		 */
		protected $connection = null;
		/**
		 * @var array An array of tables from the config file
		 */
		public $tables;
		/**
		 * @var string The salt
		 */
		public $salt = "î¹ñËyãÙOinRÃIîÃÙËì»A]9îè¨ç°²aµÌ»òdJnl¸¦Ø";
		/**
		 * @var bool Are we in debug mode?
		 */
		private $debug = true;

		/**
		 * Constructor method. First checks for a user-config.ini file, then a default-config.ini file if the user-config.ini file is not found. If both are not found, throw an error
		 */
		function __construct() {
			$dirname = dirname(__FILE__);
			if (!is_file($dirname . '/user-config.ini')) { //if the user config file isn't there
				if (!is_file($dirname . '/default-config.ini')) { //if the default config file isn't there
					trigger_error("No configuration file found!", E_USER_ERROR); //sound the alarm!
				}
				//using the default config file
				$configVals = parse_ini_file($dirname . '/default-config.ini', true);
			} else {
				//using the user config file
				$configVals = parse_ini_file($dirname . '/user-config.ini', true);
			}
			$this->database = $configVals['database']['name'];
			$this->host = $configVals['database']['host'];
			$this->user = $configVals['database']['user'];
			$this->password = $configVals['database']['password'];
			$this->tables = $configVals['tables'];
			$this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->database);

			if (mysqli_connect_errno($this->connection)) { //failed to connect
				die("Failed to connect with error: " . mysqli_connect_error());
			}
		}

		/**
		 * Connects to the database. Dies if it fails
		 */
		function connect() {
			$this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->database);
			if (mysqli_connect_errno($this->connection)) { //failed to connect
				die("Failed to connect with error: " . mysqli_connect_error());
			}
		}

		/**
		 * Closes the connection. Dies if it fails
		 */
		public function close() {
			mysqli_close($this->connection) or die("Failed to close connection with erro: " . mysqli_connect_error());
		}

		/**
		 * Connection accessor
		 * @return object MySQLi connection object
		 */
		public function getConnection() {
			return $this->connection;
		}

		/**
		 * This function fetches all results in an SQL Result
		 *
		 * @param $SQLResult object The result of an SQL query to get rows from
		 *
		 * @return array The resultant rows from the SQL query
		 */
		public function fetchAllRows($SQLResult) {
			$rows = array();
			while ($row = $SQLResult->fetch_assoc()) {
				$rows[] = $row;
			}

			return $rows;
		}

		/**
		 * This function checks if an SQL Result returned null, false, or 0 rows
		 *
		 * @param object $SQLResult The SQL Result object to check
		 *
		 * @return bool True if non-false object return, false if otherwise
		 */
		public function checkResult($SQLResult) {
			if ($SQLResult === null || $SQLResult === false || mysqli_num_rows($SQLResult) === 0) {
				return false;
			}

			return true;
		}

		/**
		 * This utility function will check the number of arguments
		 *
		 * @param integer $argumentsSupplied How many arguments were supplied
		 * @param integer $numberOfArguments How many arguments are required
		 * @param string  $class             Class name
		 * @param string  $function          Function name
		 * @param bool    $exact             Should the number be exact? Default to false
		 *
		 * @return bool True if successful
		 */
		public function checkNumberOfArguments($argumentsSupplied, $numberOfArguments, $class, $function, $exact = false) {
			if ($exact) {
				if ($argumentsSupplied != $numberOfArguments) {
					trigger_error("$class::$function requires exactly $numberOfArguments argument(s) $argumentsSupplied arguments supplied", E_USER_ERROR);

					return false;
				}
			} else {
				if ($argumentsSupplied < $numberOfArguments) {
					trigger_error("$class::$function requires at least $numberOfArguments argument(s) $argumentsSupplied arguments supplied", E_USER_ERROR);

					return false;
				}
			}

			return true;
		}

		/**
		 * This utility function will check an argument type
		 *
		 * @param mixed  $argument The argument to check
		 * @param string $type     The type the argument was is supposed to be
		 *
		 * @param string $class    The class where the error occurred
		 *
		 * @param string $function The function where the error occurred
		 *
		 * @return bool True if succeeded
		 */
		public function checkArgumentType($argument, $type, $class, $function) {
			$errorString = false;
			$real_type = gettype($argument);
			switch ($type) {
				case 'scalar':
					if (!is_scalar($argument)) {
						$errorString = "Argument(s) for $class::$function must be a $type; passed $real_type";
					}
					break;
				case 'array':
					if (!is_array($argument)) {
						$errorString = "Argument(s) for $class::$function must be an $type; passed $real_type";
					}
					break;
				case 'object':
					if (!is_object($argument)) {
						$errorString = "Argument(s) for $class::$function must be an $type; passed $real_type";
					}
					break;
				case 'string':
					if (!is_string($argument)) {
						$errorString = "Argument(s) for $class::$function must be a $type; passed $real_type";
					}
					break;
				case 'numeric':
					if (!is_numeric($argument)) {
						$errorString = "Argument(s) for $class::$function must be $type; passed $real_type";
					}
					break;
				case 'integer':
					if (!is_integer($argument)) {
						$errorString = "Argument(s) for $class::$function must be an $type; passed $real_type";
					}
					break;
				case 'int':
					if (!is_int($argument)) {
						$errorString = "Argument(s) for $class::$function must be an $type; passed $real_type";
					}
					break;
			}

			if (!$errorString) {
				return true;
			}
			trigger_error($errorString, E_USER_ERROR);

			return false;
		}

		/**
		 * Simple method to check if the supplied argument is a string and has a length greater than zero
		 *
		 * @param string|array $string   the string or array containing strings to check
		 * @param string       $class    The class where the error might occur
		 * @param string       $function The function where the error might occur
		 *
		 * @return boolean returns true if argument is a string and has a length greater than zero, false if otherwise
		 */
		function checkString($string, $class, $function) {
			if (gettype($string) == "array") {
				for ($i = 0; $i < count($string); $i++) {
					if (!is_string($string[$i]) && strlen($string[$i]) == 0) {
						trigger_error("Argument(s) for $class::$function must be a string", E_USER_ERROR);
					}
				}
			} else {
				if (!is_string($string) && strlen($string) == 0) {
					trigger_error("Argument(s) for $class::$function must be a string", E_USER_ERROR);
				}
			}

			return true;
		}

		/**
		 * Simple method to execute a query or die. Checks the query before returning
		 *
		 * @param string $query The query to run
		 * @param string $file
		 * @param string $line
		 *
		 * @internal param object $link The database link
		 * @return bool|object Returns the result or false if nothing is returned
		 */
		public function queryOrDie($query, $file, $line) {
			$this->checkString(array_slice(func_get_args(), 1), __CLASS__, __FUNCTION__);

			$result = mysqli_query($this->connection, $query);
			if (mysqli_errno($this->connection)) {
				die("Query in file: $file on line: $line failed spectacularly. Here's the error: " . mysqli_error($this->connection));
			}

			return $result;
		}

		/**
		 * Simple method to prep a string for mysqli usage
		 *
		 * @param string $string The string to prep
		 *
		 * @return string The prepared string
		 */
		public function escapeString($string) {
			$this->checkString($string, __CLASS__, __FILE__);

			return mysqli_real_escape_string($this->connection, $string);
		}
	}
 