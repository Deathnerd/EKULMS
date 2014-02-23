<?
	//dbFunctions.php
	//commonly used database functions
	class Db {

		private $database = "db516461409";
		private $password = "I3XkiJCGFEjcQTdKF5TC";
		private $host = "db516461409.db.1and1.com";
		private $user = "dbo516461409";
		private $connection;

		function __construct(){

			$this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->database);

			if(mysqli_connect_errno($this->connection)){ //failed to connect
				die("Failed to connect with error: ".mysqli_connect_error());
			}
		}

		//reconnects to the database
		function connect(){

			$this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->database);

			if(mysqli_connect_errno($this->connection)){ //failed to connect
				die("Failed to connect with error: ".mysqli_connect_error());
			}
		}

		//cleans up and cloases the database connection
		function close(){
			mysqli_close($this->connection);
		}

		function connection(){	//$connection accessor
			return $this->connection;
		}

		function setConnection($name){
		}
	}

	//functions for user data manipulation
	class Users extends Db {

		function __construct(){

			parent::__construct(); //call the parent constructor
			$this->connection = parent::connection(); //MySQL connection handler
		}

		//returns true if a user exists
		public function checkUserExists($userName){

			if(!is_string($userName)){
				trigger_error("Argument for Users::checkUserExists must be a string", E_USER_ERROR);
				return;
			}

			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName)); //sanitize input
			$sql = mysqli_query($this->connection, "SELECT * FROM `Users` WHERE userName='$userName'") or die(mysqli_error($this->connection));
			$results = $sql->fetch_array(MYSQLI_BOTH);

			if($results === NULL || $results === false){ //user doesn't exist, null returned from query
				return false;
			}

			return true;
		}

		//checks the supplied password against the one in the database. Returns true if found
		public function checkPassword($userName, $password){
			if(func_num_args() != 2){
				trigger_error("Users::checkPassword requires exactly two arguments; ".func_num_args()." supplied", E_USER_ERROR);
			}
			if(!is_string($userName) || !is_string($password)){
				trigger_error("Arguments for Users::checkPassword must be a string", E_USER_ERROR);
				return;
			}

			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$password = mysqli_real_escape_string($this->connection, strtolower($password));

			$sql = mysqli_query($this->connection, "SELECT password FROM `Users` WHERE userName='$userName'");

			if($sql === NULL || $sql === false){
				return false;
			}

			$results = $sql->fetch_array(MYSQLI_BOTH);

			if($results['password'] != $password){ //if the password supplied and the one fetched don't match
				return false;
			}

			return true;
		}

		//returns a user's row as an array or false if not found
		public function fetchUser($userName){

			if(!is_string($userName)){
				trigger_error("Argument for Users::fetchUser must be a string", E_USER_ERROR);
				return;
			}

			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$sql = mysqli_query($this->connection, "SELECT * FROM `Users` WHERE userName='$userName'");

			if($sql === NULL || $sql === false){
				return false;
			}

			//return the user row as an array
			return $sql->fetch_array(MYSQLI_BOTH);
		}

		public function createUser($userName, $password, $admin){
			//need at least two arguments
			if(func_num_args() < 2){
				trigger_error("Users::createUser requires at least two arguments; ".func_num_args()." arguments supplied", E_USER_ERROR);
				return;
			}

			//not setting admin pri)vileges
			if(func_num_args() == 2){
				if(!is_string($userName) || !is_string($password)){
					trigger_error("Arguments for Users::createUser must be a string", E_USER_ERROR);
					return;
				}

				//lowercase and sanitize inputs
				$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
				$password = mysqli_real_escape_string($this->connection, strtolower($password));

				$sql = mysqli_query($this->connection, "INSERT INTO `Users` (userName, password) VALUES ('$userName', '$password')");

				//check if the row is recorded
				if($this->fetchUser($userName) === false){
					return false;
				}

				//success!
				return true;
			}

			//setting admin admin
			//check argument types
			if(!is_string($userName) || !is_string($password)){ //check for string type on first two arguments
				trigger_error("First two arguments for Users::createUser must be a string", E_USER_ERROR);
				return;
			}

			if(!is_bool($admin)) { //check for boolean type on last argument
				trigger_error("Third argument for Users::createUser must be a boolean", E_USER_ERROR);
			}

			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$password = mysqli_real_escape_string($this->connection, strtolower($password));

			if($admin){
				$admin = 1;
			} else {
				$admin = 0;
			}

			$sql = mysqli_query($this->connection, "INSERT INTO `Users` (userName, password, admin) VALUES ('$userName', '$password', $admin)");

			//check if the row is recorded
			if($this->fetchUser($userName) === false){
				return false;
			}
			//success!
			return true;
		}
	}
?>