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

class Memcache extends AbstractConnection
{
    protected $_isSetConnectionOptions = false;
    protected $_host;
    protected $_port;
    protected $_timeout;

    public function setConnectionOptions($host, $port = 11211, $timeout = 1)
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_timeout = $timeout;

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
            throw new \Chrome\Exception('Cannot connect with no information! Call setConnectionOptions() before!');
        }

        if(!extension_loaded('memcache'))
        {
            throw new \Chrome\Exception('Cannot use memcache extension, since it was not enabled for php');
        }

        try {
            $this->_doConnect();
        } catch(\Chrome\Exception $e) {
            throw $e;
        }

        $this->_isConnected = true;

        return true;
    }

    protected function _doConnect()
    {
        $this->_connection = memcache_pconnect($this->_host, $this->_port, $this->_timeout);

        if($this->_connection === false) {
            throw new \Chrome\Exception\Database('Could not connect to memcache database', \Chrome\Exception\Database::DATABASE_EXCEPTION_CANNOT_CONNECT_TO_SERVER);
        }
    }

    public function disconnect()
    {
        // do nothing, we're using a persistent connection
    }

    public function getDefaultAdapter()
    {
        return '\Chrome\Database\Adapter\Memcache';
    }

    public function getDatabaseName()
    {
        return 'memcache';
    }
}
