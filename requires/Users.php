<?
	if(!is_file(realpath(dirname(__FILE__)).'/Db.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Db.php! Check your installation");
	}
	require_once(realpath(dirname(__FILE__))."/Db.php");

	//functions for user data manipulation
	class Users extends Db {

		protected $encrypt = NULL;
		protected $userPrivateKey = NULL;

		function __construct(){

			parent::__construct(); //call the parent constructor
			$this->connection = parent::connection(); //MySQL connection handler
			$this->encrypt = new Encryption($config['encryption']['cipher'], $config['encryption']['mode']); //new Encryption instance
		}

		//returns true if a string is a string and if its length is greater than 0
		function checkString($string){
			if(!is_string($string) && strlen($string) == 0){
				return false;
			}

			return true;
		}

		function setUserPrivateKeyVariable($userName, $userKey){
			if(!$this->checkString($userName) && !$this->checkString($userKey)){
				trigger_error("Arguments for Users::setUserPrivateKey must be a string", E_USER_ERROR);
				return;
			}

			//lowercase and sanitize user input
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$userKey = mysqli_real_escape_string($this->connection, strtolower($userKey));

			$this->userPrivateKey = $this->encrypt->makeKey($userName, $userKey, $this->config['encryption']['server_key']);
		}

		function makeUserPrivateKey($userName, $userKey){
			if(!$this->checkString($userName) || !$this->checkString($userKey)){
				trigger_error("Arguments for Users::makeUserPrivateKey must be a string", E_USER_ERROR);
				return;
			}

			//lowercase and sanitize user input
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$userKey = mysqli_real_escape_string($this->connection, strtolower($userKey));

			$this->userPrivateKey = $this->encrypt->makeKey($userName, $userKey, $this->config['encryption']['server_key']);
			
			$table = $this->tables['Users'];

			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (privateKey) VALUES ('$this->userPrivateKey')") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));
		}

		function checkUserPrivateKey($userName, $userKey){
			if(!$this->checkString($userName) || !$this->checkString($userKey) || !$this->checkString($server_key)){
				trigger_error("Arguments for Users::setUserPrivateKey must be a string", E_USER_ERROR);
				return;
			}

			//lowercase and sanitize user input
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$userKey = mysqli_real_escape_string($this->connection, strtolower($userKey));

			//generate a key to check against
			$privateKey = $this->encrypt->makeKey($userName, $userKey, $this->config['encryption']['server_key']);

			$sql = mysqli_query($this->connection, "SELECT privateKey FROM `$table` WHERE userName='$userName'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			if($sql === NULL || $sql === false){
				trigger_error("Sql query returned nothing in ".__FILE__."on line".__LINE__, E_USER_ERROR);
				return;
			}
			$results = $sql->fetch_array(MYSQLI_BOTH);

			if($results['privateKey'] == $privateKey){
				return true;
			}

			return false;

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

			if($this->key == NULL){
				trigger_error("Key not set!", E_USER_ERROR);
				return;
			}

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

			$password = $this->encrypt->encrypt($password, $this->key);
			
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

		public function create($userName, $password, $userKey){
			//need at least two arguments
			if(func_num_args() < 3){
				trigger_error("Users::create requires three arguments; ".func_num_args()." arguments supplied", E_USER_ERROR);
				return;
			}

			//check argument types
			if(!$this->checkString($userName) || !$this->checkString($password) || !$this->checkString($userKey)){ //check for string type on arguments
				trigger_error("Arguments for Users::create must be a string", E_USER_ERROR);
				return;
			}

			//lowercase and sanitize inputs
			$userName = mysqli_real_escape_string($this->connection, strtolower($userName));
			$password = mysqli_real_escape_string($this->connection, strtolower($password));

			$table = $this->tables['Users'];

			//insert user name first
			mysqli_query($this->connection, "INSERT INTO `$table` (userName) VALUES ('$userName')") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			$this->makeUserPrivateKey($userName, $userKey); //make the user's private key and store it in the database

			$sql = mysqli_query($this->connection, "SELECT * FROM `$table` WHERE userName='$userName'") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			$results = $sql->fetch_array(MYSQLI_BOTH);

			$password = $this->encrypt->encrypt($password, $results['userPrivateKey']); //encrypt the password with the user's private key
			
			$sql = mysqli_query($this->connection, "INSERT INTO `$table` (password) VALUES ('$password')") or die("Error in ".__FILE__." on line ".__LINE__.": ".mysqli_error($this->connection));

			//check if the row is recorded
			if($this->fetchUser($userName) === false || $sql === false || $sql === NULL){
				return false;
			}

			//success!
			return true;
		}
	}
?>