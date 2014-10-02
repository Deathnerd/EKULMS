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
			$this->headerContent = $this->_parseTemplate("header", array("title"             => $this->pageTitle,
			                                                             "create_script_tag" => $create_script_tag));
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

		/**
		 * Parses a template file
		 * @param string $filename The name of the template file to parse
		 * @param array $variables The variables to use while parsing
		 * @return mixed|string The template parsed with the variables replaced and ready to go
		 */
		private function _parseTemplate($filename, array $variables = null) {
			try {
				$template_file = file_get_contents(__DIR__ . DS . "templates" . DS . "$filename.html");
			} catch (Exception $e) {
				exit("Error reading template file $filename");
			}

			foreach ($variables as $name => $value) {
				$template_file = str_replace("{{" . $name . "}}", $value, $template_file);
			}

			return $template_file;
		}
	}