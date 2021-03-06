<?php

namespace Test\Chrome\Database\Connection;

class Dummy implements \Chrome\Database\Connection\Connection_Interface
{
    public $_connection = null;

    public $_isConnected = true;

    public $_connectionException = null;

    public $_connectionHandler = null;

    public function __construct($connection = null)
    {
        $this->_connection = $connection;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function disconnect()
    {

    }

    public function connect()
    {
        if($this->_connectionException !== null) {
            throw new $this->_connectionException();
        }

        if($this->_connectionHandler !== null) {
            $this->_connectionHandler->handleConnection($this);
        }
    }

    public function getDefaultAdapter()
    {
        return '\Test\Chrome\Database\Adapter\Dummy';
    }

    public function isConnected()
    {
        return $this->_isConnected;
    }

    public function setIsConnected($connected)
    {
        $this->_isConnected = $connected;
    }

    public function throwExceptionOnConnect($e)
    {
        $this->_connectionException = $e;
    }

    public function getDatabaseName()
    {
        return 'dummy';
    }
}
