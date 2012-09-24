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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.09.2012 23:47:29] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.DB.Interface
 */
abstract class Chrome_DB_Interface_Abstract implements Chrome_Exception_Processable_Interface
{

	/**
	 * Default adapter
	 */
	const CHROME_DB_INTERFACE_ABSTRACT_DEFAULT_ADAPTER = CHROME_DATABASE;

	/**
	 * Contains ID of this interface
	 *
	 * @var string
	 */
	private $_toString;

	/**
	 * Number of all initialized interfaces
	 *
	 * @var int
	 */
	private static $_instances = 0;

	/**
	 * Name of the adapter, e.g. MySQL
	 *
	 * @var string
	 */
	protected $_adapter = null;

    /**
     *
     */
    protected $_exceptionHandler = null;

	/**
	 * Constructor,<br>
	 * sets interface ID, registers Interface AND selects adapter
	 *
	 * @param string $adapter [optional]
	 * @return void
	 */
	protected function __construct($adapter = null)
	{
		// set interface id
		++self::$_instances;
		$this->_toString = self::$_instances;

		// set Adapter
		if($adapter === null) {
			$this->_adapter = self::CHROME_DB_INTERFACE_ABSTRACT_DEFAULT_ADAPTER;
		} else {
			$this->_adapter = $adapter;
		}

		// register interface
		Chrome_DB_Adapter_Abstract::registerInterface($this);
	}

    // this should every dbInterface inherit
    //abstract public function next();

	/**
	 * Clears all internal vars up
	 */
	protected function clear() {
		$this->__call('clear', array());
	}

	/**
	 * Wrapper for clear()
	 */
	protected function clean() {
		$this->clear();
	}

	/**
	 * Magic method of PHP
	 *
	 * @return int ID of this interface
	 */
	final public function __toString()
	{
		return (string) $this->_toString;
	}

	/**
	 * Gets the interface ID
	 *
	 * @return int interface ID
	 */
	final public function getID()
	{
		return (int) $this->_toString;
	}

	/**
	 * Magic method of PHP
	 *
	 *
	 * @param string $method method name
	 * @param array  $arguments arguments of the called method
	 * @return Chrome_DB_Interface_Abstract, instance of this
	 */
	final public function __call($method, $arguments)
	{
	    try {
		// call the method of Chrome_DB_Adapter_Abstract
		$return = call_user_func_array(array('Chrome_DB_Adapter_Abstract', '__callStatic'), array($method, array_merge(array(&$this), $arguments)));
		// return $this, for method chaining
        } catch(Chrome_Exception_Database $e) {
            // if no explicit exception handler is set, then pass it through so that it can be treated there correctly
            if($this->_exceptionHandler === null) {
                throw $e;
                //$this->_exceptionHandler = new Chrome_Exception_Database_Handler();
            }

            $this->_exceptionHandler->exception($e);
        }
		if($return !== null)
			return $return;

		return $this;
	}

	/**
	 * Gets the adapter for an interface
	 *
	 * @return string adapter
	 */
	final public function getAdapter()
	{
		return $this->_adapter;
	}

	/**
	 * Sets an adapter
	 *
	 * @param string $adapter name of the adapter, e.g. MySQL
	 * @return Chrome_DB_Interface_Abstract, instance of this
	 */
	final public function setAdapter($adapter)
	{
		// set internal adapter
		$this->_adapter = $adapter;

		// register interface
		Chrome_DB_Adapter_Abstract::registerInterface($this);

		// return $this, needed for method chaining
		return $this;
	}

	/**
	 * Gets the default adapter for every interface
	 *
	 * @return string default adapter
	 */
	final public static function getDefaultAdapter()
	{
		return self::CHROME_DB_INTERFACE_ABSTRACT_DEFAULT_ADAPTER;
	}

	/**
	 * Gets a new instance of an interface
	 *
	 * @return object Chrome_DB_Interface_Abstract
	 */
	public static function getInstance() {
		// only php >= 5.3
		//return new static();

		// this method should get overloaded
		throw new Chrome_Exception('Chrome_DB_Interface_Abstract::getInstance() is not overloaded!');
	}

    public function setExceptionHandler(Chrome_Exception_Handler_Interface $handler) {
        $this->_exceptionHandler = $handler;
    }

    public function getExceptionHandler() {
        return $this->_exceptionHandler;
    }
}