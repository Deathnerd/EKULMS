<?
	/**
	* Contains the Db class
	*/

	/**
	* Class for facilitating Database connections
	* @todo change from ini configuration file to JSON
	*/
	error_reporting(E_ALL);
	class Db {
		/**
		* @var string The database name from the config file
		*/
		protected $database;
		/**
		* @var string The database password from the config file
		*/
		protected $password;
		/**
		* @var string The database host from the config file
		*/
		protected $host;
		/**
		* @var string The database user from the config file
		*/
		protected $user;
		/**
		* @var object The MySQL connection object
		*/
		protected $connection;
		/**
		* @var array An array of tables from the config file
		*/
		public $tables;

		private $debug = true;

		/**
		* Constructor method. First checks for a user-config.ini file, then a default-config.ini file if the user-config.ini file is not found. If both are not found, throw an error
		*
		* @throws error Complains that configuration file is not found and halts execution
		*/
		function __construct(){
			if($this->debug){
				$site = "/site";
			} else {
				$site = "";
			}
			if(!is_file($_SERVER['DOCUMENT_ROOT'].$site."/user-config.ini")){ //if the user config file isn't there
				if(!is_file($_SERVER['DOCUMENT_ROOT'].$site.'/default-config.ini')){ //if the default config file isn't there
					trigger_error("No configuration file found!", E_USER_ERROR);//sound the alarm!
					return;
				}
				//using the default config file
				$configVals = parse_ini_file($_SERVER['DOCUMENT_ROOT'].$site.'/default-config.ini', true);
			} else {
				//using the user config file
				$configVals = parse_ini_file($_SERVER['DOCUMENT_ROOT'].$site.'/user-config.ini', true);
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

		/**
		* Connects to the database. Dies if it fails
		*/
		function connect(){
			$this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->database);
			if(mysqli_connect_errno($this->connection)){ //failed to connect
				die("Failed to connect with error: ".mysqli_connect_error());
			}
		}

		/**
		* Closes the connection. Dies if it fails
		*/
		public function close(){
			mysqli_close($this->connection) or die("Failed to close connection with erro: ".mysqli_connect_error());
		}

		/**
		* Connection accessor
		* @return object MySQLi connection object
		*/
		public function connection(){
			return $this->connection;
		}
	}
 