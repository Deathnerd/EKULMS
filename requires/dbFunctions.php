<?
	//dbFunctions.php
	//commonly used database functions
	class Db {

		private $database;
		private $password;
		private $host;
		private $user;
		private $connection;
		public $tables;

		function __construct(){
			if(!is_file('./user-config.ini')){ //if the user config file isn't there
				if(!is_file('./default-config.ini')){ //if the default config file isn't there
					trigger_error("No configuration file found!", E_USER_ERROR);//sound the alarm!
					return;
				}
				//using the default config file
				$configVals = parse_ini_file('./default-config.ini', true);
			} else {
				//using the user config file
				$configVals = parse_ini_file('./user-config.ini', true);
			}

			$this->database = $configVals['database']['name'];
			$this->host = $configVals['database']['host'];
			$this->user = $configVals['database']['user'];
			$this->password = $configVals['database']['password'];

			$this->tables = $configVals['tables'];

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

		function close(){	//cleans up and closes the database connection
			mysqli_close($this->connection);
		}

		function connection(){	//$connection accessor; returns a mysqli link object
			return $this->connection;
		}

	}

	//functions for user data manipulation
	class Users extends Db {

		function __construct(){

			parent::__construct(); //call the parent constructor
			$this->connection = parent::connection(); //MySQL connection handler
		}

		//returns true if a string is a string and if its length is greater than 0
		function checkString($string){

			if(!is_string($string) && strlen($string) == 0){
				return false;
			}

			return true;
		}

		//returns true if a user exists
		public function checkUserExists($userName){

			if(!$this->checkString($userName)){
				trigger_error("Argument for Users::checkUserExists must be a string", E_USER_ERROR);
				return;
			}

			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName)); //sanitize input
			
			$table = $this->tables['Users'];
			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE userName='$userName'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));
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

		//returns a user's row as an array or false if not found
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

		public function create($userName, $password){
			//need at least two arguments
			if(func_num_args() < 2){
				trigger_error("Users::create requires at least two arguments; ".func_num_args()." arguments supplied", E_USER_ERROR);
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
?>