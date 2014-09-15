<?
	/**
	 * This script contains the Users class
	 */

	/**
	 * This class contains methods to manipulate user data. Extends the Db class
	 */
	class Users {

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
			$sql = $DB->queryOrDie("SELECT * FROM `$table` WHERE userName='$userName';", __FILE__, __LINE__);

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
			$password = $DB->escapeString($password);
			$table = $DB->tables['Users'];
			$sql = $DB->queryOrDie("SELECT password FROM $table WHERE userName = '$userName'", __FILE__, __LINE__);

			$results = $sql->fetch_array(MYSQLI_ASSOC);
			$password .= $DB->salt;

			return password_verify($password, $results['password']);
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
			$sql = $this->Db->queryOrDie("SELECT * FROM `$table` WHERE userName='$userName'", __FILE__, __LINE__);

			if (!$DB->checkResult($sql)) {
				return false;
			}

			//return the user row as an array
			return $sql->fetch_array(MYSQLI_ASSOC);
		}

		/**
		 * This function handles user creation. The user will also have an auto-incremented numerical id associated with their account.
		 *
		 * @param string $userName The userName that will be inserted into the table
		 * @param string $password The password that will be inserted into the table
		 * @param string $email    The email to set
		 *
		 * @return boolean Return true if creation succeeded, false if it failed but did not produce an error
		 */
		public function create($userName, $password, $email) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 3, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			//lowercase and sanitize inputs
			$userName = $DB->escapeString(strtolower($userName));
			$password = password_hash($DB->escapeString($password) . $DB->salt, PASSWORD_BCRYPT);
			$email = password_hash($DB->escapeString($email) . $DB->salt, PASSWORD_BCRYPT);
			$table = $DB->tables['Users'];
			$sql = $DB->queryOrDie("INSERT INTO `$table` (userName, password, email, date_created, last_logged_in, reset_key) VALUES ('$userName', '$password', '$email', NOW(), NOW(), NULL)", __FILE__, __LINE__);

			return $this->fetchUser($userName) && $DB->checkResult($sql);
		}

		/**
		 * This function takes in a new password and the user_id and resets their password
		 *
		 * @param string $newPassword The new password
		 * @param int    $user_id     The id of the user to be affected
		 * @param string $reset_key   The reset key to check against
		 *
		 * @return bool
		 */
		public function resetPassword($newPassword, $user_id, $reset_key) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);
			$DB->checkArgumentType($user_id, 'numeric', __CLASS__, __FUNCTION__);
			$DB->checkString($newPassword, __CLASS__, __FUNCTION__);
			$DB->checkString($reset_key, __CLASS__, __FUNCTION__);

			$password = password_hash($DB->escapeString($newPassword) . $DB->salt, PASSWORD_BCRYPT);
			$reset_key = $DB->escapeString($reset_key);
			$table = $DB->tables['Users'];

			$sql = $DB->queryOrDie("UPDATE `$table` SET password='$password', reset_key=NULL WHERE id=$user_id AND reset_key <> NULL AND reset_key='$reset_key'", __FILE__, __LINE__);

			return $DB->checkResult($sql);
		}

		/**
		 * This function checks the supplied email against the supplied username and checks if they pair up.
		 *
		 * @param string $userName The username to check against
		 * @param string $email    The email to check
		 *
		 * @return bool
		 */
		public function checkEmail($userName, $email) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			$userName = $DB->escapeString(strtolower($userName));
			$emailHashed = password_verify($DB->escapeString($email) . $DB->salt, PASSWORD_BCRYPT);
			$table = $DB->tables['Users'];

			$sql = $DB->queryOrDie("SELECT `userName` FROM `$table` WHERE userName = '$userName' AND email='$emailHashed';", __FILE__, __LINE__);

			return $DB->checkResult($sql);
		}


		/**
		 * Does what it says on the tin: generates a reset key for a user
		 *
		 * @param string $userName The user name to generate a key for
		 *
		 * @return bool
		 */
		public function generateResetKey($userName) {
			$DB = $this->Db;
			$DB->checkString($userName, __CLASS__, __FUNCTION__);

			$userName = $DB->escapeString(strtolower($userName));

			$key = md5(time() . $DB->salt);

			$table = $DB->tables['Users'];

			$sql = $DB->queryOrDie("UPDATE `$table` SET reset_key='$key' WHERE userName = '$userName';", __FILE__, __LINE__);

			if (!$DB->checkResult($sql)) {
				return false;
			}

			return $sql->fetch_array(MYSQLI_ASSOC);
		}

		/**
		 * Checks if a user is enrolled in a course given a course id
		 *
		 * @param String $courseId The course to check if the user is enrolled in
		 * @param String $userName The user name to check
		 *
		 * @return bool
		 */
		public function isEnrolled($courseId, $userName) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);
			$user = $this->fetchUser($userName);
			$courseId = $DB->escapeString($courseId);
			$userId = $user['id'];

			$table = $DB->tables['Students'];

			return $DB->checkResult($DB->queryOrDie("SELECT * FROM `$table` WHERE courseId = '$courseId' AND id=$userId;", __FILE__, __LINE__));
		}

		/**
		 * This logs by destroying the session and updating the timestamp for the last_logged_out column in the Users table
		 *
		 * @param string $userName The user to log out
		 *
		 * @return bool True if successful, false if otherwise
		 */
		public function logout($userName) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 1, __CLASS__, __FUNCTION__, true);
			$DB->checkString($userName, __CLASS__, __FUNCTION__);

			$user = $this->fetchUser($userName);
			if (!$user) {
				return false;
			}
			$table = $DB->tables['Users'];
			$userName = $user['userName']; //make sure we have the actual user name that's in the database

			return session_destroy() && $DB->checkResult($DB->queryOrDie("UPDATE `$table` SET last_logged_out = NOW() WHERE userName = '$userName';", __FILE__, __LINE__));
		}

		/**
		 * Logs in the user by starting a session, initializing all the values in the user's row into the $_SESSION array
		 * (except for the password), and sets the last_logged_in value in the database for the user to NOW()
		 *
		 * @param string $userName The username to log in
		 * @param string $password Their password
		 *
		 * @return bool Returns true if successful, false if not
		 */
		public function login($userName, $password) {
			$DB = $this->Db;
			$DB->checkNumberOfArguments(func_num_args(), 2, __CLASS__, __FUNCTION__, true);
			$DB->checkString(func_get_args(), __CLASS__, __FUNCTION__);

			$userInfo = $this->fetchUser($userName);
			$table = $DB->tables['Users'];
			$userName = $userInfo['userName']; //make sure we have the actual user name that's in the database

			if (!$this->checkPassword($userName, $password) || !$DB->checkResult($DB->queryOrDie("UPDATE `$table` SET last_logged_in = NOW() WHERE userName='$userName';", __FILE__, __LINE__))) {
				return false;
			}
			session_start();
			//set the id, userName, and admin values for the session
			foreach ($userInfo as $key => $value) {
				$_SESSION[$key] = $value;
			}
			unset($_SESSION['password']); //trash the password

			return true;
		}
	}