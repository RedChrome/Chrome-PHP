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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.03.2013 17:25:21] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

/**
 * Interface between adapter and result. Both of Chrome_Database_Adapter_Interface and
 * Chrome_Database_Result_Interface extend this interface.
 * This 'interceptor' interface might be usefull cos a result can act like an adapter.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Adapter_Result_Interface
{
    /**
     * Returns the next result row
     *
     * @var mixed
     */
    public function getNext();

    /**
     * Gets the number of affeced rows. You can use this for SELECT, SHOW, INSERT,
     * UPDATE or DELETE. (This method combines *_affeced_rows and *_num_rows)
     *
     * @var int
     */
    public function getAffectedRows();

    /**
     * Returns true if the executed query has no results. False if there is at least one result
     *
     * @return boolean
     */
    public function isEmpty();

    /**
     * Returns the id of the last executed query which was an INSERT statement
     * affecting an auto_increment column.
     * Usefull if you insert a row in a database table and the id gets generated by
     * the database. Then you get the generated id by this method.
     *
     * @var int
     */
    public function getLastInsertId();
}

/**
 * Concrete interface for adapters
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Adapter_Interface extends Chrome_Database_Adapter_Result_Interface
{
    /**
     * Sends a query to database
     *
     * @param string $query query string
     * @return void
     */
    public function query($query);

    /**
     * Escapes a string to send it to database
     *
     * @param string $data
     * @return string
     */
    public function escape($data);

    /**
     * Sets for this adapter a connection
     *
     * @param Chrome_Database_Connection_Interface $connection
     * @return void
     */
    public function setConnection(Chrome_Database_Connection_Interface $connection);

    /**
     * Returns the connection object. not the actual connection!
     *
     * @return Chrome_Database_Connection_Interface
     */
    public function getConnection();

    /**
     * Clears the adapter to send a new query
     *
     * @return Chrome_Database_Adapter_Interface the cleared adapter
     */
    public function clear();

    /**
     * Returns an error message if an error occured.
     *
     * @return string the error message
     */
    public function getErrorMessage();

    /**
     * Returns an error code if an error occured
     *
     * @return int the error code
     */
    public function getErrorCode();

    /**
     * Prepares the statement to use it with this adapter
     *
     * @param string $statement
     * @return the prepared statement
     */
    public function prepareStatement($statement);
}

/**
 * Additional interface for adapters which need a database connection to work with
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
interface Chrome_Database_Adapter_Constructor_Interface
{
    /**
     * @param Chrome_Database_Connection_Interface $connection
     * @return Chrome_Database_Adapter_Interface
     */
    public function __construct(Chrome_Database_Connection_Interface $connection);
}

abstract class Chrome_Database_Adapter_Abstract implements Chrome_Database_Adapter_Interface, Chrome_Database_Adapter_Constructor_Interface
{
    protected $_connectionObject = null;

    protected $_connection = null;

    protected $_result = null;

    protected $_isEmpty = true;

    protected $_cache   = null;

    public function __construct(Chrome_Database_Connection_Interface $connection)
    {
        $this->setConnection($connection);
    }

    public function setConnection(Chrome_Database_Connection_Interface $connection)
    {
        if($connection->isConnected() === false) {
            $connection->connect();

            if($connection->isConnected() === false) {
                throw new Chrome_Exception_Database('Given connection object could not connect to database');
            }
        }

        if(($resource = $connection->getConnection()) === null ) {
             throw new Chrome_Exception_Database('Given database connection is null');
        }

        $this->_connectionObject = $connection;
        $this->_connection = $resource;
    }

    public function getConnection()
    {
        return $this->_connectionObject;
    }

    public function clear()
    {
        $adapter = clone $this;
        $adapter->_result = null;
        $adapter->_isEmpty = true;
        $adapter->_cache = null;
        return $adapter;
    }

    public function prepareStatement($statement)
    {
        // replace table prefix
        return str_replace('cpp_', DB_PREFIX . '_', $statement);
    }
}
