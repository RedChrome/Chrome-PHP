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

namespace Chrome\Database\Connection;

class Mysql extends AbstractConnection
{
    protected $_isSetConnectionOptions = false;
    protected $_host;
    protected $_username;
    protected $_password;
    protected $_clientFlags;
    protected $_database;
    protected $_port;

    public function setConnectionOptions($host, $username, $password, $database, $port = 3306, $clientFlags = 0)
    {
        $this->_host = $host;
        $this->_username = $username;
        $this->_password = $password;
        $this->_clientFlags = $clientFlags;
        $this->_database = $database;
        $this->_port = $port;

        $this->_isSetConnectionOptions = true;
    }

    public function connect()
    {
        if(version_compare(PHP_VERSION, '5.5.0') >= 0)
        {
            throw new \Chrome\Exception('Extension MySQL is deprecated as of PHP 5.5.0, use MySQLi instead.');
        }

        if($this->_isConnected === true)
        {
            return true;
        }

        if($this->_isSetConnectionOptions === false)
        {
            throw new \Chrome\Exception('Cannot connect with no information! Call setConnectionOptions() before!');
        }

        try {
            $this->_doConnect();
            $this->_selectDb();
        } catch(\Chrome\Exception $e) {
            $this->_unsetConfig();
            throw $e;
        }

        $this->_isConnected = true;

        $this->_unsetConfig();

        return true;
    }

	protected function _unsetConfig()
	{
	    unset($this->_password, $this->_username, $this->_database, $this->_host, $this->_clientFlags, $this->_port);
	}

    protected function _doConnect()
    {
        try
        {
            $this->_connection = mysql_connect($this->_host . ':' . $this->_port, $this->_username, $this->_password);
        } catch(\Chrome\Exception $e)
        {
            switch(mysql_errno())
            {

                case 2002: // no break
                case 2003: // no break
                case 2005:
                    {
                        throw new \Chrome\Exception\Database('Could not establish connection to server  on "' . $this->_host . '"! Server is not responding!', \Chrome\Exception\Database::DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER);
                    }

                case 1045:
                    {
                        throw new \Chrome\Exception\Database('Could not establish connection to server  on "' . $this->_host . '"! Username and/or password is wrong', \Chrome\Exception\Database::DATABASE_EXCEPTION_WRONG_USER_OR_PASSWORD);
                    }

                default:
                    {
                        throw new \Chrome\Exception\Database('(' . mysql_errno() . ') ' . mysql_error(), \Chrome\Exception\Database::UNKNOWN);
                    }
            }
        }
    }

    protected function _selectDb()
    {
        try
        {
            mysql_select_db($this->_database, $this->_connection);
        } catch(\Chrome\Exception $e)
        {
            switch(mysql_errno($this->_connection))
            {
                case 1049:
                    {
                        throw new \Chrome\Exception\Database('Could not select database ' . $this->_database . '!', \Chrome\Exception\Database::DATABASE_EXCEPTION_CANNOT_SELECT_DATABASE);
                    }

                default:
                    {
                        throw new \Chrome\Exception\Database('(' . mysql_errno() . ') ' . mysql_error(), \Chrome\Exception\Database::UNKNOWN, $e);
                    }
            }
        }
    }

    public function disconnect()
    {
        // do nothing, we're using a persistent connection
    }

    public function getDefaultAdapter()
    {
        return '\Chrome\Database\Adapter\Mysql';
    }

    public function getDatabaseName()
    {
        return 'mysql';
    }
}
