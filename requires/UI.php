<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/4/14
	 * Time: 10:32 PM
	 */

	class UI {
		public $debug = true;

		private $pageTitle = "";
		private $headerContent = "";

		private $footerContent = "</body>
									</html>";

		function __construct($title = "") {
			$this->pageTitle = $title;
			$this->headerContent = "<!DOCTYPE html>
								<html>
								<head>
									<title>$title</title>
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
		}

		public function show($section) {
			echo $this->{strtolower($section) . "Content"};
		}

		public function set($section, $contents) {
			$this->{strtolower($section) . "Content"} = $contents;
		}

		public function get($section, $contents) {
			return $this->{strtolower($section) . "Content"} = $contents;
		}

		public function getPageTitle() {
			return $this->pageTitle;
		}

		//currently useless
		public function setPageTitle($title) {
			$this->pageTitle = $title;
		}
	}