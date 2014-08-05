<?php
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 8/4/14
 * Time: 10:28 PM
 */

class Footer {
	private static $debug = true;

	private static $content = "</body>
							</html>";

	public static function show() {
		echo self::$content;
	}

	public static function setContent($content){
		self::$content = $content;
	}

	public static function getContent(){
		return self::$content;
	}

	public static function setDebug($debug){
		self::$debug = $debug;
	}

	public static function getDebug(){
		return self::$debug;
	}
} 