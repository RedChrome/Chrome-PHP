<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [14.04.2013 21:17:52] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * Interface for a database connection
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Connection_Interface
{
    /**
     * Returns the database connection
     *
     * @return mixed the database connection
     */
    public function getConnection();

    /**
     * disconnect the database
     *
     * @return void
     */
    public function disconnect();

    /**
     * Connects to a database. The needed information can be set in setConnectionOptions(). See for
     * further information about setConnectionOptions() the concrete implementation.
     *
     * @return bool if connection was successfully established, false else
     */
    public function connect();

    /**
     * Returns true if connected to database
     *
     * @return bool true if connected to database, false else
     */
    public function isConnected();

    /**
     * Returns the default adapter suffix.
     *
     * Every connection has a default adapter which is compatible with the connection. This method returns
     * the suffix to identify the adapter class
     *
     * @return string default adapter suffix
     */
    public function getDefaultAdapterSuffix();
}

/**
 * Additional interface to retrieve a schema
 *
 * Some databases allow the usage of schemas. To retrieve the schema, implement this interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Connection_SchemaProvider_Interface
{
    /**
     * Returns the schema for the current connection
     *
     * @return string
     */
    public function getSchema();
}

/**
 * Interface for a connection registry
 *
 * These classes are storing the connection, associated with a connection name
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Registry_Connection_Interface
{
    /**
     * name of the default connection
     *
     * @var string
     */
    const DEFAULT_CONNECTION = 'default';

    /**
     * Adds a new connection with the given name.
     *
     * This method will throw an exception if $overwrite = false and the conenction name already exists.
     *
     * @param string $name name of the connection
     * @param Chrome_Database_Connection_Interface connection object containing the actual database connection
     * @param bool $overwrite [optional] true if you want to overwrite an existing connection name
     */
    public function addConnection($name, Chrome_Database_Connection_Interface $connection, $overwrite = false);

    /**
     * Returns the connection inside the connection object
     *
     * This will throw an exception if $name does not exist or the connection was not established
     *
     * @param string $name name of the connection
     * @return mixed the connection
     */
    public function getConnection($name);

    /**
     * Returns all available connections
     *
     * @return array containing connection names (as string)
     */
    public function getConnections();

    /**
     * Returns the connection object (set by addConnection) with the given name
     *
     * @param string $name name of the connection
     * @return Chrome_Database_Connection_Interface the connection object associated by $name
     */
    public function getConnectionObject($name);

    /**
     * Determines whether the connection with the name $name has established a connection
     *
     * @return bool true if $name exists and is connected
     */
    public function isConnected($name);

    /**
     * Determines whether the connection with the name $name exists in this registry
     *
     * @return bool true if $name exists
     */
    public function isExisting($name);
}

abstract class Chrome_Database_Connection_Abstract implements Chrome_Database_Connection_Interface
{
    protected $_connection  = null;

    protected $_isConnected = false;

    public function getConnection()
    {
        return $this->_connection;
    }

    public function isConnected()
    {
        return $this->_isConnected;
    }
}

class Chrome_Database_Registry_Connection implements Chrome_Database_Registry_Connection_Interface
{
    protected $_connections = array();

    public function __construct()
    {
    }

    public function addConnection($name, Chrome_Database_Connection_Interface $connection, $overwrite = false)
    {
        if(isset($this->_connections[$name]) AND $overwrite !== true) {
            throw new Chrome_Exception_Database('Cannot re-set an existing database connection with name "' . $name . '"!');
        }

        $this->_connections[$name] = $connection;
    }

    public function getConnection($name)
    {
        if(!isset($this->_connections[$name])) {
            throw new Chrome_Exception_Database('Could not find connection with name "' . $name . '"!');
        }

        if($this->_connections[$name]->isConnected() === false) {
            throw new Chrome_Exception_Database('Cannot get connection, if connection is not established!');
        }

        return $this->_connections[$name]->getConnection();
    }

    public function getConnectionObject($name)
    {
        if(!isset($this->_connections[$name])) {
            throw new Chrome_Exception_Database('Could not find connection object with name "' . $name . '"!');
        }

        return $this->_connections[$name];
    }

    public function isConnected($name)
    {
        return $this->isExisting($name) ? $this->_connections[$name]->isConnected() : false;
    }

    public function isExisting($name)
    {
        return isset($this->_connections[$name]);
    }

    public function getConnections()
    {
        return array_keys($this->_connections);
    }
}

