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
 * @subpackage Chrome.DB
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [12.08.2011 13:08:06] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @todo implement Chrome_Registry_Interface,  OR check whether it is compatible with it
 * @package CHROME-PHP
 * @subpackage Chrome.DB
 */
class Chrome_DB_Registry
{
	/**
	 * Instance of this class, used for singleton pattern
	 *
	 * @var Chrome_DB_Registry
	 */
	private static $_instance;

	/**
	 * Contains all database connections
	 *
	 * @var array
	 */
	private $_connections;

	/**
	 * Constructor, used for singleton pattern
	 *
	 * @return void
	 */
	private function __construct()
	{
		$this->_connections = array();
	}

	/**
	 * Gets the instance of this class
	 *
	 * @return Chrome_DB_Registry instance
	 */
	public static function getInstance()
	{
		if(self::$_instance === null) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Sets a connection for an adapter
	 *
	 * @param Chrome_DB_Adapter_Abstract $obj instance of an adapter
	 * @return void
	 */
	public function setConnectionForAdapter(Chrome_DB_Adapter_Abstract &$obj)
	{
		// sets the connection
		$obj->setConnectionByRegistry($this, $this->_getConnection($obj->getConnectionID()));
	}

	/**
	 * Fetches the conneciton from $this->_connections
	 *
	 * @param int $connectionID connection ID
	 * @return resource connection to database
	 */
	private function _getConnection($connectionID)
	{
		// check wheter connection exists
		if(!isset($this->_connections[$connectionID])) {
			throw new Chrome_Exception_DB('Cannot get connection by ID ' . $connectionID . '! Connection does not exist!', 1);
		}

		return $this->_connections[$connectionID];
	}

	/**
	 * Creates a new connection
	 *
	 * @param Chrome_DB_Adapter_Abstract $obj instance of an adapter
	 * @param string                     $server name of the server, e.g. localhost
	 * @param string                     $database name of the database, e.g. chrome
	 * @param string                     $user username
	 * @param string                     $pass password
	 * @return int 						 Connection ID
	 */
	public function createConnection(Chrome_DB_Adapter_Abstract &$obj, $server, $database, $user, $pass)
	{
		// try to connect to database, using the adapter
        $this->_connections[] = $obj->createConnection($server, $database, $user, $pass);

		// returns the connection ID
		return sizeof($this->_connections) - 1;
	}

	/**
	 * Get a connection by ID
	 *
	 * @param int $connectionID connection ID
	 * @return resource, connection to database
	 */
	public function getConnection($connectionID)
	{
		return $this->_getConnection($connectionID);
	}
}