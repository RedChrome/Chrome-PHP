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
 * @package    CHROME-PHP
 * @subpackage Chrome.Database
 */
namespace Chrome\Database\Connection;

/**
 * Interface for a database connection
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Connection_Interface
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
     * Returns the name of the database, in lower case, just digits and letters
     *
     * @return string
     */
    public function getDatabaseName();

    /**
     * Returns the default adapter suffix.
     *
     * Every connection has a default adapter which is compatible with the connection. This method returns
     * the class name to work properly with the database
     *
     * @return string default adapter suffix
     */
    public function getDefaultAdapter();
}

/**
 * Additional interface to retrieve a schema
 *
 * Some databases allow the usage of schemas. To retrieve the schema, implement this interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface SchemaProvider_Interface
{
    /**
     * Returns the schema for the current connection
     *
     * @return string
     */
    public function getSchema();
}

/**
 * An implementation of the basic methods for a database connection holder
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
abstract class AbstractConnection implements Connection_Interface
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

namespace Chrome\Database\Registry;

/**
 * Interface for a connection registry
 *
 * These classes are storing the connection, associated with a connection name
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Connection_Interface
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
     * @param \Chrome\Database\Connection\Connection_Interface connection object containing the actual database connection
     * @param bool $overwrite [optional] true if you want to overwrite an existing connection name
     */
    public function addConnection($name, \Chrome\Database\Connection\Connection_Interface $connection, $overwrite = false);

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
     * @return \Chrome\Database\Connection\Connection_Interface the connection object associated by $name
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

/**
 * A complete implementation of a database connection registry
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class Connection implements Connection_Interface
{
    protected $_connections = array();

    public function __construct()
    {
    }

    public function addConnection($name, \Chrome\Database\Connection\Connection_Interface $connection, $overwrite = false)
    {
        if(isset($this->_connections[$name]) AND $overwrite !== true) {
            throw new \Chrome\DatabaseException('Cannot re-set an existing database connection with name "' . $name . '"!');
        }

        $this->_connections[$name] = $connection;
    }

    public function getConnection($name)
    {
        if(!isset($this->_connections[$name])) {
            throw new \Chrome\DatabaseException('Could not find connection with name "' . $name . '"!');
        }

        if($this->_connections[$name]->isConnected() === false) {
            throw new \Chrome\DatabaseException('Cannot get connection, if connection is not established!');
        }

        return $this->_connections[$name]->getConnection();
    }

    public function getConnectionObject($name)
    {
        if(!isset($this->_connections[$name])) {
            throw new \Chrome\DatabaseException('Could not find connection object with name "' . $name . '"!');
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

