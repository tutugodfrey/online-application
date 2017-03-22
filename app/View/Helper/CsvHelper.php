<?php
/**
 * CakePHP CSV Output Helper
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *                        1785 E. Sahara Avenue, Suite 490-423
 *                        Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
 * @link      http://bakery.cakephp.org/articles/view/csv-helper-php5
 * @package   views
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CSV Output Helper
 *
 * @package		View
 * @subpackage	View.Helper
 */
class CsvHelper extends AppHelper {

/**
 * Delimiter
 *
 * @var string
 * @access public
 */
	public $delimiter = ',';

/**
 * Enclosure
 *
 * @var string
 * @access public
 */
	public $enclosure = '"';

/**
 * Filename
 *
 * @var string
 * @access public
 */
	public $filename = 'export.csv';

/**
 * Line array
 *
 * @var array
 * @access public
 */
	public $line = array();

/**
 * Buffer file resource
 *
 * @var resource
 * @access public
 */
	public $buffer; 

/**
 * 
 */
	public function CsvHelper() {
		$this->clear(); 
	}

/**
 * clear
 *
 * @access public
 * @return void
 */
	public function clear() {
		$this->line = array();
		$this->bufferFile = APP . 'tmp' . DS . 'cache' . DS . 'maxmemory-' . CakeText::uuid() . '-'. (5 * 1024 * 1024);
		$this->buffer = fopen($this->bufferFile, 'a+'); 
	}

/**
 * addField
 *
 * @param string
 * @access public
 * @return void
 */
	public function addField($value) {
		$this->line[] = $value; 
	}

/**
 * endRow
 *
 * @access public
 * @return void
 */
	public function endRow() { 
		$this->addRow($this->line); 
		$this->line = array(); 
	}

/**
 * addRow
 *
 * @access public
 * @return void
 */
	public function addRow($row) { 
		fputcsv($this->buffer, $row, $this->delimiter, $this->enclosure); 
	}

/**
 * renderHeaders
 *
 * @access public
 * @return void
 */
	public function renderHeaders() {
		header("Content-type:application/vnd.ms-excel");
		header("Content-disposition:attachment;filename=" . $this->filename);
	}

/**
 * Sets the filename
 *
 * @param string
 * @access public
 */
	public function setFilename($filename) {
		$this->filename = $filename;
		if (strtolower(substr($this->filename, -4)) != '.csv') {
			$this->filename .= '.csv'; 
		}
	}

/**
 * Render
 *
 * @param 
 * @param 
 * @param
 * @return
 * @access public
 */
	public function render($outputHeaders = true, $toEncoding = null, $fromEncoding = "auto") { 
		if ($outputHeaders) {
			if (is_string($outputHeaders)) {
				$this->setFilename($outputHeaders);
			}
			$this->renderHeaders();
		}
		rewind($this->buffer); 
		$output = stream_get_contents($this->buffer);
		fclose($this->buffer);
		if ($toEncoding) {
			$output = mb_convert_encoding($output, $toEncoding, $fromEncoding);
		}
		return $this->output(trim($output));
	}

/**
 * afterRender callback
 *
 * @return void
 */
	public function afterRender($viewFile) {
		@unlink($this->bufferFile);
	}
	
}