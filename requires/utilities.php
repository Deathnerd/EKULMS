<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 6/22/14
	 * Time: 4:30 PM
	 */
//namespace utils;

//	use Carbon\Carbon;

	class Utilities/* extends Carbon*/
	{
		function __construct(Db $db) {
			$this->DB = $db;
		}

		/**
		 * @param int $line The line that the error occurred on
		 * @param string $functionName The function where the error occurred
		 * @param string $className The class where the error occurred
		 * @param string $fileName The file where the error occurred
		 */
		private function _raiseError($line, $functionName, $className, $fileName) {
			error_reporting(E_ALL);
			trigger_error("ERROR IN $className::$functionName IN FILE $fileName ON LINE $line", E_USER_ERROR);
		}

		/**
		 * @param string $path_to_JSON The full or relative path to the JSON file
		 *
		 * @return array An associative array of the file contents
		 */
		public function importFromJSONFile($path_to_JSON) {
			if (!is_string($path_to_JSON) || !is_file($path_to_JSON)) {
				$this->_raiseError(__LINE__, __FUNCTION__, __CLASS__, __FILE__);
			}

			return json_decode(fopen($path_to_JSON, 'r'), true);
		}

		/**
		 * Constructs a single element array with a blank array value to make into a JSON object when the array structure is encoded.
		 * if the key is not null, then it returns an unkeyed array. Otherwise, it returns a keyed array
		 *
		 * @param null|string $key the key to name the JSON object
		 *
		 * @return array
		 */
		public function jsonObject($key = null) {
			if (is_string($key) && !is_null($key)) {
				return array($key => array());
			}

			return array(array());
		}

		/**
		 * Returns a blank array to use as a JSON array
		 * @return array
		 */
		public function jsonArray() {
			return array();
		}

		/**
		 * Prints an object inside a <pre> tag using print_r() for easy reading
		 *
		 * @param mixed $thingToPrint the object to output with a print_r()
		 */
		public function print_pre($thingToPrint) {
			foreach (func_get_args() as $arg) {
				?>
				<pre><? print_r($arg) ?></pre><br/><?
			}
		}

		/**
		 * Simple method to check if a file exists and die with a message if not
		 *
		 * @param string $location Where is the file?
		 * @param string $file Where did we call this function?
		 * @param string $line What line did we call this function at?
		 */
		public function checkFile($location, $file, $line) {
			if (!is_file($location)) {
				$file_name = end(explode('/', $location)); //get the actual file name
				die("Error in $file on line $line: Cannot find $file_name! Check your installation");
			}
		}

		/**
		 * Takes any number of arguments, runs them through a print_r, and echos them out in a <pre> tag
		 */
		public function printPre() {
			foreach (func_get_args() as $argument) {
				$stuff = print_r($argument, true);
				echo "<pre>$stuff</pre><br/>";
			}
		}

		/**
		 * This overeager function checks if a variable is set, empty, null-string, or just plain null in that order.
		 * If any argument meets the above requirements, the respective error is echoed out.
		 *
		 * @param array $vars The variables to check. It must be an array of variables, even if the variable itself is an array
		 * @param array $errors The respective errors to echo for each variable
		 *
		 * @return bool True if all passed, false if not
		 */
		public function checkIsSet($vars, $errors) {
			$returnVal = true;
			for ($i = 0; $i < count($vars); $i++) {
				if (!isset($vars[$i]) || empty($vars[$i]) || $vars[$i] == "" || $vars[$i] == null) {
					$returnVal = false;
					echo $errors[$i];
				}
			}

			return $returnVal;
		}

		/**
		 * This is a shortcut method that closes a database connection and exits with a message if one is supplied.
		 * It also sets the Content-type to application/text in the header for easy parsing/displaying on the client
		 *
		 * @param string $message The message to echo upon exit
		 *
		 * @param bool $json_request Is this json?
		 * @internal param \Db $DB The database object that has the active connection
		 */
		function exitWithMessage($message = "", $json_request = false) {
			header_remove("Content-type");
			if ($json_request) {
				header("Content-type: application/json");
			} else {
				header("Content-type: application/text");
			}
			exit($message);
		}


		/**
		 * This is a shortcut method to redirect a client and exit the script
		 *
		 * @param string $location The location to bounce back to
		 */
		public function redirectTo($location) {
			header("Location: $location");
			exit();
		}

		/**
		 * A nice wrapper to send emails using PHPMailer
		 *
		 * @access public
		 * @param array $to A multi-level array with the structure of such:
		 * <code>
		 * array(
		 *     array(
		 *         "name" => $name,
		 *         "address" => $address,
		 *         "reply_to" => $reply_to (optional)
		 *     )
		 * )
		 * </code>
		 *
		 * @param array $from An array with the structure similar to $to, but without "reply_to" key
		 * @param string $subject The subject of the email
		 * @param string $body The HTML body of the email
		 * @param array $config_vals The config values for the email. Requires the following values:
		 *                                 host, port, user_name, password
		 * @param null|array $cc An array with the structure similar to $from
		 * @param null|array $bcc An array with the sturcture similar to $cc
		 *
		 * @internal param \Db $Db Requires a Database object for error checking
		 * @return bool
		 */
		public function sendEmail($to, $from, $subject, $body, $config_vals, $cc = null, $bcc = null) {
			$Db = $this->DB;

			$Db->checkArgumentType($to, 'array', __CLASS__, __FUNCTION__);
			$Db->checkArgumentType($from, 'array', __CLASS__, __FUNCTION__);
			$Db->checkArgumentType($config_vals, 'array', __CLASS__, __FUNCTION__);
			$Db->checkString(array($subject, $body), __CLASS__, __FUNCTION__);

			$Mailer = new PHPMailer();
			try {
				$Mailer->Host = $config_vals['host'];
				$Mailer->SMTPDebug = 2;
				$Mailer->SMTPAuth = true;

				$Mailer->SMTPSecure = "tls";
				$Mailer->Port = $config_vals['port'];

				$Mailer->Username = $config_vals['user_name'];
				$Mailer->Password = $config_vals['password'];

				foreach ($from as $sender) {
					$Mailer->setFrom($sender['address'], $sender['name']);
					if (isset($sender['reply_to'])) {
						$Mailer->addReplyTo($sender['address'], $sender['name']);
					}
				}

				foreach ($to as $recepient) {
					$Mailer->addAddress($recepient['address'], $recepient['name']);
				}

				if (!is_null($cc)) {
					$Db->checkArgumentType($cc, 'array', __CLASS__, __FUNCTION__);
					foreach ($cc as $ccTo) {
						$Mailer->addCC($ccTo['address'], $ccTo['name']);
					}
				}
				if (!is_null($bcc)) {
					$Db->checkArgumentType($bcc, 'array', __CLASS__, __FUNCTION__);
					foreach ($bcc as $bccTo) {
						$Mailer->addBCC($bccTo['address'], $bccTo['name']);
					}
				}

				$Mailer->Subject = $subject;
				$Mailer->msgHTML($body);
				$Mailer->send();
			} catch (phpmailerException $e) {
				$this->exitWithMessage($e->errorMessage());
			} catch (Exception $e) {
				$this->exitWithMessage($e->getMessage());
			}

			return true;
		}

		/**
		 * A recursive array search
		 * @param mixed $needle What are we looking for?
		 * @param array $haystack Where are we looking for it
		 * @return bool|int|string
		 */
		function recursive_array_search($needle, $haystack) {
			foreach ($haystack as $key => $value) {
				$current_key = $key;
				if ($needle === $value OR (is_array($value) && $this->recursive_array_search($needle, $value) !== false)) {
					return $current_key;
				}
			}

			return false;
		}
	}