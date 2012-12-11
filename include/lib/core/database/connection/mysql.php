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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.12.2012 18:31:47] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

class Chrome_Database_Connection_Mysql extends Chrome_Database_Connection_Abstract
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
        $this->_host        = $host;
        $this->_username    = $username;
        $this->_password    = $password;
        $this->_clientFlags = $clientFlags;
        $this->_database    = $database;
        $this->_port        = $port;

        $this->_isSetConnectionOptions = true;
    }

    public function connect()
    {
        if($this->_isConnected === true) {
            return true;
        }

        if($this->_isSetConnectionOptions === false) {
            throw new Chrome_Exception('Cannot connect with no information! Call setConnectionOptions() before!');
        }

        $this->_connection = @mysql_connect($this->_host . ':' . $this->_port, $this->_username, $this->_password);

        if($this->_connection === false) {
            switch(mysql_errno()) {

                case 2002:
                case 2003:
                case 2005:
                    {
                        throw new Chrome_Exception_Database('Could not establish connection to server  on "' . $this->_host . '"! Server is not responding!', Chrome_Exception_Database::DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER);
                    }

                case 1045:
                    {
                        throw new Chrome_Exception_Database('Could not establish connection to server  on "' . $this->_host . '"! Username and/or password is wrong', Chrome_Exception_Database::DATABASE_EXCEPTION_WRONG_USER_OR_PASSWORD);
                    }

                default:
                    {

                        throw new Chrome_Exception_Database('(' . mysql_errno() . ') ' . mysql_error(), Chrome_Exception_Database::DATABASE_EXCEPTION_UNKNOWN);
                    }
            }
        }

        if(@mysql_select_db($this->_database, $this->_connection) === false) {
            switch(mysql_errno($this->_connection)) {
                case 1049:
                    {
                        throw new Chrome_Exception_Database('Could not select database ' . $this->_database . '!', Chrome_Exception_Database::DATABASE_EXCEPTION_CANNOT_SELECT_DATABASE);
                    }

                default:
                    {
                        throw new Chrome_Exception_Database('(' . mysql_errno() . ') ' . mysql_error(), Chrome_Exception_Database::DATABASE_EXCEPTION_UNKNOWN);
                    }
            }
        }

        $this->_isConnected = true;

        unset($this->_password, $this->_username, $this->_database, $this->_host, $this->_clientFlags, $this->_port);

        return $this->_connection;
    }

    public function disconnect()
    {
        // do nothing, we're using a persistent connection
    }

    public function getDefaultAdapter()
    {
        return 'Mysql';
    }
}
