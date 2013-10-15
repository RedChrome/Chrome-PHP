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
 * @package CHROME-PHP
 * @subpackage Chrome.Database
 */

class Chrome_Database_Connection_Mysqli extends Chrome_Database_Connection_Abstract
{
    protected $_isSetConnectionOptions = false;
    protected $_host;
    protected $_username;
    protected $_password;
    protected $_socket;
    protected $_database;
    protected $_port;

    protected $_isPersistent = false;

    public function setConnectionOptions($host, $username, $password, $database, $port = 3306, $socket = null)
    {
        if(!extension_loaded('mysqli'))
        {
            throw new Chrome_Exception('Extension MySQLi not loaded! Cannot use this adapter');
        }

        $this->_host = $host;
        $this->_username = $username;
        $this->_password = $password;
        $this->_socket = $socket;
        $this->_database = $database;
        $this->_port = (int) $port;

        // persistent connection
        if(ini_get('mysqli.allow_persistent') == 1 and stripos($this->_host, 'p:') === false)
        {
            $this->_isPersistent = true;
            $this->_host = 'p:' . $this->_host;
        }

        $this->_isSetConnectionOptions = true;
    }

    public function connect()
    {
        if($this->_isConnected === true)
        {
            return true;
        }

        if($this->_isSetConnectionOptions === false)
        {
            throw new Chrome_Exception('Cannot connect to MySQL Server without any information about the server! Call setConnectionOptions() before!');
        }

        $this->_doConnect();

        $this->_isConnected = true;

        return true;
    }

    protected function _doConnect()
    {
        try
        {
            $this->_connection = new mysqli($this->_host, $this->_username, $this->_password, $this->_database, $this->_port, $this->_socket);
        } catch(Chrome_Exception $e)
        {

            switch(mysqli_connect_errno())
            {
                // TODO: add other exceptions

                case 1040: // too much connections
                case 1044: // cannot access database
                case 2002: // cant connect through socket
                case 2003: // cannot connect to server
                case 2005:
                    {
                        throw new Chrome_Exception_Database('Could not establish connection to server on "tcp://' . $this->_host . ':' . $this->_port . '"!', Chrome_Exception_Database::DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER);
                    }

                case 1045: // wrong password
                    {
                        throw new Chrome_Exception_Database('Could not connect to MySQL Server. Wrong Username and/or password', Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_USER_OR_PASSWORD);
                    }
                default:
                    {
                        throw new Chrome_Exception_Database($e->getMessage(), Chrome_Exception_Database::DATABASE_EXCEPTION_UNKNOWN);
                    }
            }
        }
    }

    public function disconnect()
    {
        // no connection established, cannot disconnect
        if(!($this->_connection instanceof mysqli) )
        {
            return;
        }

        // only disconnect, if we havens set up a persistent connection
        if($this->_isPersistent === false)
        {
            $this->_connection->close();
        }
    }

    public function getDefaultAdapterSuffix()
    {
        return 'Mysqli';
    }

    public function getDatabaseName()
    {
        return 'mysql';
    }
}
