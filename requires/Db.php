<?
	//Db.php stuff
	//class of methods to handle core database functions
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
?>