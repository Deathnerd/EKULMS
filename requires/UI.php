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
		public $debug = DEBUG_UI;

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
		 * @var array The array of variables to parse with the template
		 */
		public $template_variables = array();

		/**
		 * Retrieve the current template variables
		 * @return array
		 */
		public function getTemplateVariables() {
			return $this->template_variables;
		}

		/**
		 * Set the template variables to be parsed
		 * @param array $template_variables
		 * @return $this
		 */
		public function setTemplateVariables($template_variables) {
			$this->template_variables = $template_variables;

			return $this;
		}

		/**
		 * @param string $page The filename of the page
		 * @param string $title The title of the page
		 */
		function __construct($page = "", $title = "") {
			$this->pageTitle = $title;
			$create_script_tag = ($page != "" && $page == "create.php") ? "<script type='text/javascript' src='js/create.js'></script>" : "";
			$this->template_variables = array("title"             => $this->pageTitle,
			                                  "create_script_tag" => $create_script_tag,
			                                  "site_root"         => SITE_ROOT);
		}

		/**
		 * Show the section with the specified name
		 *
		 * @access public
		 * @param string $section The section to show
		 * @return $this
		 */
		public function show($section) {
			echo $this->{strtolower($section) . "Content"};

			return $this;
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
		 *
		 * @return $this
		 */
		public function setPageTitle($title) {
			$this->pageTitle = $title;

			return $this;
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

			//clean up any left over variables before we send it out
			return preg_replace('/{{.*}}/', "", $template_file);
		}

		/**
		 * Parse the template
		 * @param string $template The name of the template to parse
		 * @return $this
		 */
		public function executeHeaderTemplate($template) {
			$this->headerContent = $this->_parseTemplate($template, $this->template_variables);

			return $this;
		}

		/**
		 * This does an array_merge on the supplied array and the existing template variables array
		 * @param array $array The array of template variables to add
		 * @return $this
		 */
		public function addToTemplateVariables(array $array) {
			$this->template_variables = array_merge($this->template_variables, $array);

			return $this;
		}

		/**
		 * A function that loops through the supplied array and performs an unset()
		 * operation on the template_variables array for each key provided
		 *
		 * @param array $array An array of strings of the keys to remove from the array
		 * @return $this
		 */
		public function removeFromTemplateVariables(array $array) {
			foreach ($array as $var) {
				unset($this->template_variables[$var]);
			}

			return $this;
		}


		/**
		 * A function that loops through the given array and updates or adds the respective
		 * values in/to the template_variables array
		 * @param array $array
		 * @return $this
		 */
		public function updateTemplateVariables(array $array) {
			foreach ($array as $key => $value) {
				$this->template_variables[$key] = $value;
			}

			return $this;
		}
	}