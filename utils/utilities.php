<?php
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 6/22/14
 * Time: 4:30 PM
 */

//namespace utils;


class Utilities {
	/**
	 * @param int    $line The line that the error occurred on
	 * @param string $functionName The function where the error occurred
	 * @param string $className The class where the error occurred
	 * @param string $fileName The file where the error occurred
	 */
	private function _raiseError($line , $functionName , $className , $fileName){
		error_reporting(E_ALL);
		trigger_error("ERROR IN $className::$functionName IN FILE $fileName ON LINE $line", E_USER_ERROR);
	}

	/**
	 * @param string $path_to_JSON The full or relative path to the JSON file
	 * @return array An associative array of the file contents
	 */
	public function importFromJSONFile($path_to_JSON){
		if(!is_string($path_to_JSON) || !is_file($path_to_JSON)){
			$this->_raiseError(__LINE__, __FUNCTION__, __CLASS__, __FILE__);
		}
		return json_decode(fopen($path_to_JSON, 'r'), true);
	}

	/**
	 * Constructs a single element array with a blank array value to make into a JSON object when the array structure is encoded.
	   if the key is not null, then it returns an unkeyed array. Otherwise, it returns a keyed array
	 *
	 * @param null|string $key the key to name the JSON object
	 *
	 * @return array
	 */
	public function jsonObject($key=null){
		if(is_string($key) && $key){
			return array($key => array());
		}
		return array( array());
	}

	/**
	 * Returns a blank array to use as a JSON array
	 * @return array
	 */
	public function jsonArray(){
		return array();
	}

	/**
	 * Simple method to check if a file exists and die with a message if not
	 *
	 * @param string $location Where is the file?
	 * @param string $file Where did we call this function?
	 * @param string $line What line did we call this function at?
	 */
	function checkFile($location, $file, $line){
		if (!is_file($location)) {
			$file_name = end(explode('/', $location)); //get the actual file name
			die("Error in $file on line $line: Cannot find $file_name! Check your installation");
		}
	}

	/**
	 * Takes any number of arguments, runs them through a print_r, and echos them out in a <pre> tag
	 */
	function printPre(){
		foreach(func_get_args() as $argument){
			$stuff = print_r($argument, true);
			echo "<pre>$stuff</pre><br/>";
		}
	}
}