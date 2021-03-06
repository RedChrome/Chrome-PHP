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
 * Connection class for DB2 databases
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */
class DB2 extends AbstractConnection
{
    protected $_isSetConnectionOptions = false;

    protected $_host;
    protected $_username;
    protected $_password;
    protected $_clientFlags;
    protected $_database;
    protected $_port;
    protected $_connectionString;
    protected $_options;

    public function setConnectionOptions($host, $username, $password, $database, $port = 50000, $connectionString = null, $options = array())
    {
        if(!extension_loaded('db2')) {
            throw new \Chrome\Exception('Extension DB2 not loaded! Cannot use this adapter');
        }

        $this->_host        = $host;
        $this->_username    = $username;
        $this->_password    = $password;
        $this->_clientFlags = $clientFlags;
        $this->_database    = $database;
        $this->_port        = $port;
        $this->_options     = $options;

        if($connectionString !== null) {
            $this->_connectionString = $connectionString;
        } else {
            // set connection string appropriate to the given authorisation data

            //TODO: set connection string for db2
            $this->_connectionString = 'DATABASE='.$database.';HOSTNAME='.$hostname.';PORT='.$port.';';

        }

        $this->_isSetConnectionOptions = true;
    }

    public function connect()
    {
        if($this->_isConnected === true) {
            return true;
        }

        if($this->_isSetConnectionOptions === false) {
            throw new \Chrome\Exception('Cannot connect with no information! Call setConnectionOptions() before!');
        }
        try {
            $this->_connection = db2_pconnect($this->_connectionString, $this->_username, $this->_password, $this->_options);
        } catch(\Chrome\Exception $e) {
            $this->_unsetConfig();
           // TODO: handle errors
           throw $e;
        }

        $this->_isConnected = true;

        $this->_unsetConfig();


        return $this->_connection;
    }
	public function _unsetConfig()
	{
        unset($this->_password, $this->_username, $this->_database, $this->_host, $this->_clientFlags, $this->_port, $this->_options, $this->_connectionString);
	}


    public function disconnect()
    {
        // do nothing, we're using a persistent connection
    }

    public function getDefaultAdapter()
    {
        return '\Chrome\Database\Adapter\DB2';
    }

    public function getDatabaseName()
    {
        return 'db2';
    }
}
