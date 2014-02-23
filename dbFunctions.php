<?
	//dbFunctions.php
	//commonly used database functions
	class Db {
		private $databaseName = "db516461409";
		private $password = "I3XkiJCGFEjcQTdKF5TC";
		private $host = "db516461409.db.1and1.com";
		private $user = "dbo516461409";
		private $connection = mysqli_connect($host, $user, $password, $databaseName);

		function __construct(){
			if(mysqli_connect_errorno($connection)){ //failed to connect
				die("Failed to connect with error: ".mysqli_connect_error());
			}
		}

		function connection(){	//$connection accessor
			return $connection;
		}
	}

	//functions for user data manipulation
	class Users extends Db {

		function __construct(){
			parent::__construct(); //call the parent constructor
			$connection = parent::connection(); //MySQL connection handler
		}

		public function checkUserExits(string $userName){
			mysqli_escape_string($userName); //sanitize input
			$sql = mysqli_query("SELECT FROM users (username) WHERE username=$userName");

			if(sqlite_num_rows($sql) > 0){ //user exists
				return true;
			}

			return false;
		}

		public function fetchUser(string $userName){
			mysqli_escape_string($userName);
			$sql = mysqli_query("S");
		}
	}
?>