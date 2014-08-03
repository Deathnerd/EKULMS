<?
	/**
	 * This script contains the Users class
	 */

	/**
	 * This class contains methods to manipulate user data. Extends the Db class
	 */
	class Users{

		private $Db = null;
		/**
		 * Constructor method
		 * @var Db $database the database object to use
		 */
		function __construct(Db $database) {
			$this->Db = $database;
		}

		/**
		 * This function checks if a user exists in the user table
		 *
		 * @param string $userName The user's name in the database
		 *
		 * @return boolean returns true if the operation completed successfully, false if it failed but did not produce an error
		 */
		public function userExists($userName) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);
			$DB->checkString($userName, __CLASS__, __FUNCTION__);
			//lowercase and sanitize inputs
			$userName = $DB->escapeString(strtolower($userName)); //sanitize input
			$table = $DB->tables['Users'];
			$sql = $DB->queryOrDie("SELECT * FROM `$table` WHERE userName='$userName'", __FILE__, __LINE__);

			return $DB->checkResult($sql);
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
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			//lowercase and sanitize inputs
			$userName = $DB->escapeString(strtolower($userName));
			$password = $DB->escapeString(strtolower($password));
			$table = $DB->tables['Users'];
			$sql = $DB->queryOrDie("SELECT password FROM $table WHERE userName = '$userName'", __FILE__, __LINE__);

			if (!$DB->checkResult($sql)) {
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
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);
			$DB->checkString($userName, __CLASS__, __FUNCTION__);
			//lowercase and sanitize inputs
			$userName = $DB->escapeString(strtolower($userName));
			$table = $DB->tables['Users'];
			$sql =  $this->Db->queryOrDie("SELECT * FROM `$table` WHERE userName='$userName'", __FILE__, __LINE__);

			if (!$DB->checkResult($sql)) {
				return false;
			}

			//return the user row as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		/**
		 * This function handles user creation. The user will also have an auto-incremented numerical id associated with their account.
		 *
		 * @param string $userName The userName that will be inserted into the table
		 * @param string $password The password that will be inserted into the table
		 *
		 * @return boolean Return true if creation succeeded, false if it failed but did not produce an error
		 */
		public function create($userName, $password) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			//lowercase and sanitize inputs
			$userName = $DB->escapeString(strtolower($userName));
			$password = $DB->escapeString(strtolower($password));
			$table = $DB->tables['Users'];
			$sql =  $DB->queryOrDie("INSERT INTO `$table` (userName, password) VALUES ('$userName', '$password')", __FILE__, __LINE__);

			return $this->fetchUser($userName) && $DB->checkResult($sql);
		}
	}