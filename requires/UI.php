<?php

	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/4/14
	 * Time: 10:32 PM
	 */

	class UI
	{

		/**
		 * @access public
		 * @var bool Are we in debug mode?
		 */
		public $debug = true;

		/**
		 * @access private
		 * @var string The page title
		 */
		private $pageTitle = "";

		/**
		 * @access private
		 * @var string The header content
		 */
		private $headerContent = "";

		/**
		 * @access private
		 * @var string The footer content
		 */
		private $footerContent = "</body>
									</html>";

		/**
		 * @param string $page The filename of the page
		 * @param string $title The title of the page
		 */
		function __construct($page = "", $title = "") {
			$this->pageTitle = $title;
			$create_script_tag = ($page != "" && $page == "create.php") ? "<script type='text/javascript' src='js/create.js'></script>" : "";
			$this->headerContent = "<!DOCTYPE html>
								<html>
								<head>
									<title>$title</title>
									<meta name='description' content='Quiz Creation'>
									<meta name='author' content='Wes Gilleland'>
									<meta name='published' content='TODO'>
									<script type='text/javascript' src='js/jquery-2.1.0.min.js'></script>
								$create_script_tag
								<script type='text/javascript' src='js/main.js'></script>
								<link type='text/css' rel='stylesheet' href='css/reset.css'>
								<link type='text/css' rel='stylesheet' href='css/main.css'>
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
									<a href='logout.php'>Log out</a>
								</header>";
		}

		/**
		 * Show the section with the specified name
		 *
		 * @access public
		 * @param string $section The section to show
		 */
		public function show($section) {
			echo $this->{strtolower($section) . "Content"};
		}

		/**
		 * Setter for a section's content
		 *
		 * @access public
		 * @param string $section The section to show
		 * @param string $contents The contents of the section to set
		 */
		public function set($section, $contents) {
			$this->{strtolower($section) . "Content"} = $contents;
		}

		/**
		 * Getter for a section's content
		 *
		 * @access public
		 * @param string $section The section to get content for
		 * @return mixed
		 */
		public function get($section) {
			return $this->{strtolower($section) . "Content"};
		}

		/**
		 * Getter for a the page title
		 *
		 * @access public
		 * @return string The current page title
		 */
		public function getPageTitle() {
			return $this->pageTitle;
		}

		/**
		 * Setter for the page title
		 *
		 * @access public
		 * @param string $title The title to set
		 */
		public function setPageTitle($title) {
			$this->pageTitle = $title;
		}
	}