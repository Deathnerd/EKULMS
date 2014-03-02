<?
	//Db.php
	//class of methods to handle core database functions

	if(!is_file(realpath(dirname(__FILE__)).'/Encryption.php')){
		die("Error in ".__FILE__." on line ".__LINE__.": Cannot find Encryption.php! Check your installation");
	}
	require_once(realpath(dirname(__FILE__))."/Encryption.php");

	class Db {

		protected $database;
		protected $password;
		protected $host;
		protected $user;
		protected $connection;
		protected $config;
		public $tables;

		function __construct(){
			if(!is_file('./user-config.ini')){ //if the user config file isn't there
				if(!is_file('./default-config.ini')){ //if the default config file isn't there
					trigger_error("No configuration file found!", E_USER_ERROR);//sound the alarm!
					return;
				}
				//using the default config file
				$this->config = parse_ini_file('./default-config.ini', true);
			} else {
				//using the user config file
				$this->config = parse_ini_file('./user-config.ini', true);
			}

			$this->database = $this->config['database']['name'];
			$this->host = $this->config['database']['host'];
			$this->user = $this->config['database']['user'];
			$this->password = $this->config['database']['password'];

			$this->tables = $this->config['tables'];

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