<?
	/**
	* This script contains the Users class
	*/

	if(!is_file(realpath(dirname(__FILE__)).'/Db.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Db.php! Check your installation");
	}
	require_once(realpath(dirname(__FILE__))."/Db.php");
	/**
	* This class contains methods to manipulate user data. Extends the Db class
	* @uses Db::__construct()
	*/
	class Users extends Db {
		/**
		* Constructor method
		* @uses Db::__construct()
		* @var object $this->connection returned from parent::connection()
		*/
		function __construct(){
			parent::__construct()//call the parent constructor
			$this->connection = parent::connection()//MySQL connection handler
		}

		/**
		* Simple method to check if the supplied argument is a string and has a length greater than zero
		* @param string $string the string to check
		* @return boolean returns true if argument is a string and has a length greater than zero, false if otherwise
		*/
		function checkString($string){
			if(!is_string($string) && strlen($string) == 0){
				return false;
			}
			return true;
		}

		/**
		* This function checks if a user exists in the user table
		* @param string $userName The user's name in the database
		* @return boolean returns true if the operation completed successfully, false if it failed but did not produce an error
		*/
		public function checkUserExists($userName){
			if(!$this->checkString($userName)){
				trigger_error("Argument for Users::checkUserExists must be a string", E_USER_ERROR);
				return;
			}
			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName))//sanitize input
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE userName='$userName'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));
			$results = $sql->fetch_array(MYSQLI_BOTH);

			if($results === NULL || $results === false){ //user doesn't exist, null returned from query
				return false;
			}
			return true;
		}
		/**
		* This function checks the user password against theh one stored in the database
		* @param string $userName The username associated with the password being checked
		* @param string $password The password string being checked
		* @return boolean Returns true if a match, false if it failed but did not produce an error
		*/
		public function checkPassword($userName, $password){
			if(func_num_args() != 2){
				trigger_error("Users::checkPassword requires exactly two arguments".func_num_args()." supplied", E_USER_ERROR);
				return;
			}
			if(!$this->checkString($userName) || !$this->checkString($password)){
				trigger_error("Arguments for Users::checkPassword must be a string", E_USER_ERROR);
				return;
			}
			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$password = mysqli_real_escape_string($this->connection, strtolower($password));
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "SELECT password FROM `$table` WHERE userName='$userName'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			if($sql === NULL || $sql === false){
				return false;
			}
			$results = $sql->fetch_array(MYSQLI_BOTH);

			if($results['password'] != $password){ //if the password supplied and the one fetched don't match
				return false;
			}
			return true;
		}
		/**
		* This function retrieves a users's row as both a keyed and a non-keyed array
		* @param string $userName The username whose row you want to fetch
		* @return array|boolean Return an array that has both keyed and non-keyed values or false if the row was not found
		*/
		public function fetchUser($userName){
			if(!$this->checkString($userName)){
				trigger_error("Argument for Users::fetchUser must be a string", E_USER_ERROR);
				return;
			}
			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE userName='$userName'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			if($sql === NULL || $sql === false){
				return false;
			}
			//return the user row as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}
		/**
		* This function handles user creation. The user will also have an auto-incremented numerical id associated with their account. This function MUST have two arguments. 
		* @param string $userName The userName that will be inserted into the table
		* @param string $password The password that will be inserted into the table
		* @return boolean Return true if creation succeeded, false if it failed but did not produce an error
		*/
		public function create($userName, $password){
			//need at least two arguments
			if(func_num_args() < 2){
				trigger_error("Users::create requires at least two arguments".func_num_args()." arguments supplied", E_USER_ERROR);
				return;
			}
			//check argument types
			if(!$this->checkString($userName) || !$this->checkString($password)){ //check for string type on first two arguments
				trigger_error("First two arguments for Users::create must be a string", E_USER_ERROR);
				return;
			}
			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$password = mysqli_real_escape_string($this->connection, strtolower($password));
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (userName, password) VALUES ('$userName', '$password')") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));
			//check if the row is recorded
			if($this->fetchUser($userName) === false || $sql === false || $sql === NULL){
				return false;
			}
			//success!
			return true;
		}
	}