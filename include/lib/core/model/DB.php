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
 * @subpackage Chrome.Model
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.09.2012 14:45:34] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_DB_Abstract extends Chrome_Model_Abstract
{
    /**
     * Adapter name e.g. MySQL
     * <optional>
     *
     * @var string
     */
	protected $_dbAdapter				= null;

    /**
     * Which Interface gets used? e.g. Iterator
     * <optional>
     *
     * @var string
     */
	protected $_dbInterface				= null;

    /**
     * Server address
     * <optional>
     *
     * @var string
     */
	protected $_dbServer				= null;

    /**
     * Database name
     * <optional>
     *
     * @var string
     */
	protected $_dbDatabase 				= null;

    /**
     * Database user
     * <optional>
     *
     * @var string
     */
	protected $_dbUser					= null;

    /**
     * Database password
     * <optional>
     *
     * @var string
     */
	protected $_dbPassword				= null;

    /**
     * If no escaper is set, then connect to the default database
     *
     * @var bool
     */
    protected $_useDefaultDBConnectionForEscaper = true;

    /**
     * Database Object to escape data
     *
     * @var Chrome_DB_Adapter_Abstract
     */
    protected $_escaper                 = null;

    /**
     * Contains the instance of an DB_Interface
     *
     * @var Chrome_DB_Interface_Abstract
     */
	protected $_dbInterfaceInstance 	= null;

	/**
	 * Chrome_Model_DB_Abstract::__construct()
	 *
	 * @return Chrome_Model_DB_Abstract
	 */
	protected function __construct() {
        $this->_connect();
	}

	/**
	 * Chrome_Model_DB_Abstract::_connect()
	 *
     * Connects to a database, using $_dbAdapter, $_dbInterface, $_dbServer, $_dbDatabase, $_dbUser AND $_dbPassword
     * if any value is changed, this creates a interface with these values.
     * if nothing is changed it creates a default interface with the default connection settings
     *
	 * @return void
	 */
	protected function _connect() {

		if( $this->_dbServer	!== null OR
		 	$this->_dbDatabase	!== null OR
			$this->_dbUser 		!== null OR
			$this->_dbPassword	!== null ) {

			$this->_dbInterfaceInstance = Chrome_DB_Interface_Factory::factory($this->_dbInterface, $this->_dbAdapter);
			$this->_dbInterfaceInstance->connect($this->_dbServer, $this->_dbDatabase, $this->_dbUser, $this->_dbPassword);
		} else {
			$this->_dbInterfaceInstance = Chrome_DB_Interface_Factory::factory($this->_dbInterface);
		}
	}

	/**
	 * Chrome_Model_DB_Abstract::_getNewDBInterface()
	 *
     * Creates a new Interface for database access,
     * the new interface instance is saved in $_dbInterfaceInstance
     *
	 * @param Chrome_DB_Interface_Abstract $interface instance of the old interface, if you want the same connection settings
	 * @return void
	 */
	protected function _getNewDBInterface(Chrome_DB_Interface_Abstract $interface = null) {

		$interface = ($interface === null) ? $this->_dbInterface : $interface;

		$conID = $this->_dbInterfaceInstance->getConnectionID();

		$instance = Chrome_DB_Interface_Factory::factory($interface);
		$instance->setConnectionID($conID);

		$this->_dbInterfaceInstance = $instance;
	}

    /**
     * Chrome_Model_DB_Abstract::_escape()
     *
     *
     * @param mixed $data data that gets escaped
     * @return mixed escaped data
     */
    protected function _escape($data) {
        if($this->_escaper !== null) {
            return $this->_escaper->escape($data);
        }

        return $this->_dbInterfaceInstance->escape($data);
    }

    /**
     * Chrome_Model_DB_Abstract::setDBInterface()
     *
     * Sets the dbInterfaceInstnace object with the given one
     *
     * @param Chrome_Form_Interface_Abstract
     * @return void
     */
    public function setDBInterface(Chrome_Form_Interface_Abstract $dbInterface) {
        $this->_dbInterfaceInstance = $dbInterface;
    }

    /**
     * Chrome_Form_Interface_Abstract::getDBInterface()
     *
     * You should not use this internal! Only for access from outside of this class
     * Getter for $_dbInterfaceInstance
     *
     * @return Chrome_Form_Interface_Abstract|null
     */
    public function getDBInterface() {
        return $this->_dbInterfaceInstance;
    }

    /**
     * Chrome_Form_Interface_Abstract::_getDBInterface()
     *
     * This is the corresponding method for getDBInterface, but for internal usage!
     * It creates a new dbInterfaceInstance if the current one is null. This will never
     * return null, getDBInterface might return null...
     *
     * @return Chrome_Form_Interface_Abstract
     */
    protected function _getDBInterface() {
        if($this->_dbInterfaceInstance === null) {
            $this->_connect();
        }

        return $this->_dbInterfaceInstance;
    }


}