<?
	/**
	 * This script contains the Users class
	 */

	if (!is_file(realpath(dirname(__FILE__)) . '/Db.php')) {
		die("Error in " . __FILE__ . " on line " . __LINE__ . ": Cannot find Db.php! Check your installation");
	}
	require_once(realpath(dirname(__FILE__)) . "/Db.php");

	/**
	 * This class contains methods to manipulate user data. Extends the Db class
	 * @uses Db::__construct()
	 */

	class Users extends Db {
		protected $connection = null;

		/**
		 * Constructor method
		 * @uses Db::__construct()
		 * @var object $this ->connection returned from parent::connection()
		 */
		function __construct() {
			parent::__construct(); //call the parent constructor
			$this->connection = parent::getConnection(); //MySQL connection handler
		}

		/**
		 * This function checks if a user exists in the user table
		 *
		 * @param string $userName The user's name in the database
		 *
		 * @return boolean returns true if the operation completed successfully, false if it failed but did not produce an error
		 */
		public function userExists($userName){
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_num_args(), true);
			$this->checkString($userName, __CLASS__, __FUNCTION__);
			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName)); //sanitize input
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE userName='$userName'") or die('Error in ' . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			return $this->checkResult($sql);
		}

		/**
		 * This function checks the user password against the one stored in the database
		 *
		 * @param string $userName The username associated with the password being checked
		 * @param string $password The password string being checked
		 *
		 * @return boolean Returns true if a match, false if it failed but did not produce an error
		 */
		public function checkPassword($userName, $password) {
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 2, func_num_args(), true);
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$password = mysqli_real_escape_string($this->connection, strtolower($password));
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "SELECT password FROM `$table` WHERE userName='$userName'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (!$this->checkResult($sql)) {
				return false;
			}
			$results = $sql->fetch_array(MYSQLI_BOTH);

			if ($results['password'] != $password) { //if the password supplied and the one fetched don't match
				return false;
			}

			return true;
		}

		/**
		 * This function retrieves a users's row as both a keyed and a non-keyed array
		 *
		 * @param string $userName The username whose row you want to fetch
		 *
		 * @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		 */
		public function fetchUser($userName) {
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 1, func_get_args(), true);
			$this->checkString($userName, __CLASS__, __FUNCTION__);
			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE userName='$userName'") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			if (!$this->checkResult($sql)) {
				return false;
			}

			//return the user row as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		 * This function handles user creation. The user will also have an auto-incremented numerical id associated with their account. This function MUST have two arguments.
		 *
		 * @param string $userName The userName that will be inserted into the table
		 * @param string $password The password that will be inserted into the table
		 *
		 * @return boolean Return true if creation succeeded, false if it failed but did not produce an error
		 */
		public function create($userName, $password) {
			$this->checkNumberOfArguments(__CLASS__, __FUNCTION__, 2, func_num_args(), true);
			$this->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$password = mysqli_real_escape_string($this->connection, strtolower($password));
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (userName, password) VALUES ('$userName', '$password')") or die("Error in " . __FILE__ . " on line " . __LINE__ . ": " . mysqli_error($this->connection));

			return $this->fetchUser($userName) && $this->checkResult($sql);
		}
	}