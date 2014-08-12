<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/4/14
	 * Time: 10:01 PM
	 */

	class Header {
		private static $debug = true;

		private static $content = "<!DOCTYPE html>
                                    <html>
                                    <head>
                                        <meta name='description' content='Quiz Creation'>
                                        <meta name='author' content='Wes Gilleland'>
                                        <meta name='published' content='TODO'>
                                        <script type='text/javascript' src='scripts/jquery-2.1.0.min.js'></script>
                                    <script type='text/javascript' src='scripts/create.js'></script>
                                    <script type='text/javascript' src='scripts/main.js'></script>
                                    <link type='text/css' rel='stylesheet' href='styles/reset.css'>
                                    <link type='text/css' rel='stylesheet' href='styles/main.css'>
                                    </head>
                                    <body>
                                    <header id='topNav'>
                                        <div id='logo'>
                                            LOGO HERE
                                        </div>
                                        <div id='dropdown'>
                                            <p>DROPDOWN HERE</p>
                                        </div>
                                        <p id='pageTitle'>Quiz Creation</p>
                                    </header>";

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