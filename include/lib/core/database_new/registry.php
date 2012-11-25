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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.11.2012 00:16:59] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true) die();

interface Chrome_Database_Registry_Connection_Interface
{
    public static function getInstance();

    public function addConnection($name, Chrome_Database_Connection_Interface $connection);

    public function getConnection($name);

    public function getConnectionObject($name);

    public function isConnected($name);

    public function isExisting($name);
}

class Chrome_Database_Registry_Connection implements Chrome_Database_Registry_Connection_Interface
{
    protected $_connections = array();

    private static $_instance = null;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function addConnection($name, Chrome_Database_Connection_Interface $connection)
    {
        if(isset($this->_connections[$name])) {
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

    /**
     * Returns true if connection exists and connection is established
     * false else
     *
     * @param string $name name of the connection
     * @return bool
     */
    public function isConnected($name)
    {
        return $this->isExisting($name) ? $this->_connections[$name]->isConnected() : false;
    }

    public function isExisting($name)
    {
        return isset($this->_connections[$name]);
    }
}
