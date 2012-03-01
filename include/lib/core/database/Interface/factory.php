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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:41:57] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.DB.Interface
 */
class Chrome_DB_Interface_Factory
{
	const CHROME_DB_INTERFACE_FACTORY_DEFAULT_INTERFACE = 'Iterator';

	private static $_instance;

	private $_loadedInterfaces;

	public static function factory($interface = null, $adapter = null)
	{
		if($interface === null) {
			$interface = self::CHROME_DB_INTERFACE_FACTORY_DEFAULT_INTERFACE;
		}

		$obj = self::getInstance();

		$obj->_loadInterface($interface);
		return $obj->_createInterface($interface, $adapter);
	}

	private function __construct() {
		$this->_loadedInterfaces = array();
	}

	private function __clone() {}

	public static function getInstance()
	{
		if(self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function _loadInterface($interface)
	{
		// workaround for php 5.3
		$interface{0} = strtolower($interface{0});

		if(isset($this->_loadedInterfaces[$interface])) {
			return true;
		}

		if(!_isFile(LIB.'core/database/Interface/'.$interface.'.php')) {
			throw new Chrome_Exception('Cannot load file '.LIB.'core/database/Interface/'.$interface.'.php! File does not exist in Chrome_DB_Interface_Factory::_loadInterface()!');
		}

		require_once LIB.'core/database/Interface/'.$interface.'.php';

		$this->_loadedInterfaces[$interface] = true;
	}

	private function _createInterface($interface, $adapter = null)
	{
		$class = 'Chrome_DB_Interface_'.$interface;

		return new $class($adapter);
	}
}