<?php

	class Table {

		public $header = array();

		public $rows = array();

		public $cells = array();

		public $tableAttributes = array("align"       => null,
		                                "bgcolor"     => null,
		                                "border"      => null,
		                                "cellpadding" => null,
		                                "cellspacing" => null,
		                                "frame"       => null,
		                                "rules"       => null,
		                                "sortable"    => null,
		                                "summary"     => null,
		                                "width"       => null
		);

		private $html = "";

		/**
		 * Begins the creation of a new table. With this, it is also possible to set the id, class, and attributes
		 * of the table tag.
		 *
		 * @param string $id         The id(s) to add to the tag
		 * @param string $class      The class(es) to add to the tag
		 * @param string $attributes The attribute(s) to add to the tag
		 *
		 * @internal param string $tattributes The HTML attributes to add to the tag
		 */
		function __construct($id = null, $class = null, $attributes = null) {
			$numericals = array("cellspacing", "cellpadding", "width");
			if ($attributes) { //if there are attributes, then parse them
				$attribs = explode(" ", $attributes);

				foreach ($attribs as $attrib) { //map attributes to key => value relationship
					list($key, $value) = explode("=", $attrib); //ex: width="30" becomes (width, "30")
					if (in_array($key, $numericals)) {
						$value = intval($value);
					}
					$this->tableAttributes[$key] = $value;
				}
			}

			//add the attributes to the opening tag
			$properties = "";
			$properties .= $id ? " id='$id'" : "";
			$properties .= $class ? " class='$class'" : "";
			$properties .= $attributes ? " $attributes" : "";

			$this->html = "<table $properties> </table>";
		}

		/**
		 * This function flushes the class to a variable and sets it up for another table generation. Takes the same arguments
		 * as the constructor (because it uses the constructor to set up the table
		 *
		 * @param null|string $id
		 * @param null|string $class
		 * @param null|string $attributes
		 *
		 * @return string
		 */
		/*public function flush($id = null, $class = null, $attributes = null) {
			$this->__construct($id, $class, $attributes);
			$html = str_split($this->html, " ");
			$body = "";
			foreach($this->rows as $cell){
				$body .= "<tr>";
				for($i = 0; $i < $cell; $i++){
					$body .= "<td ";
					$cellAttributes = $cell['attributes'];
					$cellAttributes = $cell['attributes'];
					$cellAttributes = $cell['attributes'];
					$body .= $cellAttributes
					$body .= "></td>";
				}
				$body .= "</tr>";
			}
			$returnVal = $this->html;
			$this->reset();

			return $returnVal;
		}*/

		/**
		 * This function takes an array of row(s) and inserts them at the specified position. If position is not given, then
		 * it inserts them at the end of the rows
		 *
		 * @param array $cells      A single-depth array of cells to add to the rows. They may have the following attributes,
		 *                          each relating to an html property: attributes, content, id, class.
		 *
		 * @param null  $position
		 *
		 * @return bool|int
		 */
		public function addRow($cells, $position = null) {
			$width = $this->tableAttributes['width'];
			//width enforcement
			$cells_length = count($cells);
			if ($cells_length > $width && $width != null) {
				return false;
			}

			$rowsLength = count($this->rows);
			$pos = is_null($position) ? $rowsLength - 1 : $position;

			$temp = array();
			$this->_constructCell($temp, array('attributes', 'id', 'class', 'content'));

			//enforcement of attributes existing
			/*$cells['content'] = array_key_exists('content', $cells) ? $cells['content'] : "";
			$cells['attributes'] = array_key_exists('attributes', $cells) ? $cells['attributes'] : "";
			$cells['class'] = array_key_exists('class', $cells) ? $cells['class'] : "";
			$cells['id'] = array_key_exists('id', $cells) ? $cells['id'] : "";*/

			/*if (array_key_exists('content', $cells) && is_array(is_array($cells['content']))) {
				$temp = array();
				for($i = 0;  $i < $cells_length; $i++){
					foreach ($cells[$i]['content'] as $content) {
						$temp['content'] .= "$content ";
					}
					rtrim($temp['content']);
					$cells[$i]['content'] = $temp['content'];
				}
			} else {
				$cells['content'] = null;
			}

			if (array_key_exists('id', $cells) && is_array(is_array($cells['id']))) {
				$temp = array();
				for($i = 0; $i < $cells_length; $i++){
					foreach ($cells[$i]['id'] as $content) {
						$temp['id'] .= "$content ";
					}
					rtrim($temp['id']);
					$cells[$i]['id'] = $temp['id'];
				}
			} else {
				$cells['id'] = null;
			}*/

			/*if (array_key_exists('attributes', $cells) && is_array(is_array($cells['attributes']))) {
				$temp = array();
				for($i = 0; $i < $cells_length; $i++){
					foreach ($cells[$i]['attributes'] as $content) {
						$temp['attributes'] .= "$content ";
					}
					rtrim($temp['attributes']);
					$cells[$i]['attributes'] = $temp['attributes'];
				}
			} else {
				$cells['attributes'] = null;
			}*/

			//return how many were added. Can be used as a true/false check

			foreach ($temp as $cell) {
				$this->_insertAt($pos, $cell, $this->rows);
			}

			return true;
		}

		/**
		 * Fetches a row from an index. If no arguments are given, returns the last row. If a string, then checks if string
		 * is 'first' or 'last', and returns the respective row. If is integer, then
		 *
		 * @param null|string|int $pos Takes a string, integer representing the index of the row to return, or a null value
		 *                             to return the last row
		 *
		 * @return bool
		 */
		public function getRow($pos = null) {
			$rowCount = count($this->rows);
			if ($rowCount > 0 && $pos < $rowCount) {
				if (!is_null($pos)) {
					if (is_numeric($pos)) {
						return $this->rows[$pos];
					} elseif (is_string($pos)) {
						switch ($pos) {
							case 'first':
								return $this->rows[0];
							case 'last':
								return $this->rows[$rowCount - 1];
							default:
								return false;
						}
					}
				}

				return $this->rows[$rowCount - 1];
			}

			return false;
		}

		/**
		 * @param array $cells
		 * @param array $attribs
		 *
		 * @internal param int $cells_length
		 */
		private function _constructCell(&$cells, $attribs) {
			$cells_length = count($cells);
			foreach ($attribs as $attrib) {
				if (array_key_exists($attrib, $cells) && is_array(is_array($cells[$attrib]))) {
					$temp = array();
					for ($i = 0; $i < $cells_length; $i++) {
						foreach ($cells[$i][$attrib] as $content) {
							$temp[$attrib] .= "$content ";
						}
						rtrim($temp[$attrib]);
						$cells[$i][$attrib] = $temp[$attrib];
					}
				} else {
					$cells[$attrib] = null;
				}
			}
		}

		/**
		 * This private method will insert an array into another array at the specified position
		 *
		 * @param int   $pos      The index to insert at
		 * @param array $needle   The array to insert
		 * @param array $haystack The array to insert into
		 *
		 * @return array The resultant array
		 */
		private function _insertAt($pos, $needle, &$haystack) {
			$beginningArray = array_slice($haystack, 0, $pos, true);
			$endingArray = array_slice($haystack, $pos, count($haystack) - 1, true);

			return $beginningArray + $needle + $endingArray;
		}

		public function reset() {
			$this->html = null;
			$this->header = array();
			$this->rows = array();
			$this->cells = array();
			foreach ($this->tableAttributes as $areYouSatisfiedPHP) {
				$this->tableAttributes = null;
			}
		}
	}