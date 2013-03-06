<?php

class Chrome_Database_Connection_Dummy implements Chrome_Database_Connection_Interface
{
    public $_connection = null;

    public $_isConnected = true;

    public $_connectionException = null;

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
    }

    public function getDefaultAdapterSuffix()
    {
        return 'Dummy';
    }

    public function isConnected() {
        return $this->_isConnected;
    }

    public function setIsConnected($connected)
    {
        $this->_isConnected = $connected;
    }

    public function throwExceptionOnConnect($e) {
        $this->_connectionException = $e;
    }
}
