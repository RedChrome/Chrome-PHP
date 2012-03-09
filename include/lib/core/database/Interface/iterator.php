<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.DB.Interface
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [06.03.2012 14:16:12] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.DB.Interface
 */
class Chrome_DB_Interface_Iterator extends Chrome_DB_Interface_Abstract implements Iterator
{
	private $_iteratorPointer = 0;
	private $_iteratorArray = array();
	private $_iteratorLastPosition = 0;

	public function __construct($adapter = null) {
		parent::__construct($adapter);
	}

	public function current() {
		return $this->_iteratorArray[$this->_iteratorPointer];
	}

	public function key() {
		return $this->_iteratorPointer;
	}

	public function next() {
		++$this->_iteratorPointer;

		// if rewind was called, only add a next result if last position + 1 == current position
		if($this->_iteratorLastPosition + 1  == $this->_iteratorPointer) {

			$this->_iteratorLastPosition = $this->_iteratorPointer;

            $this->_iteratorArray[$this->_iteratorPointer] = Chrome_DB_Adapter_Abstract::__callStatic('fetchResult', array(&$this));

			// fetch next result from adapter
			//$this->_iteratorArray[$this->_iteratorPointer] = call_user_func_array(array('Chrome_DB_Adapter_Abstract', '__callStatic'), array('fetchResult', array(&$this)));
		}
	}

	public function rewind() {
		$this->_iteratorPointer = 0;
		// on first time, we need to call next() to start iterating
		if($this->_iteratorLastPosition == 0) {
			$this->next();
		}
	}

	public function valid() {
		return (isset($this->_iteratorArray[$this->_iteratorPointer]) AND $this->_iteratorArray[$this->_iteratorPointer] !== false);
	}

	public static function getInstance() {
		return new self();
	}

	/**
	 * truncates all internal vars
	 */
	public function clear() {
		$this->_iteratorArray = array();
		$this->_iteratorLastPosition = 0;
		$this->_iteratorPointer = 0;
		parent::clear();
	}
}