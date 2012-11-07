<?php

class Chrome_Database_Connection_Dummy implements Chrome_Database_Connection_Interface
{
    public $_connection = null;

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

    }

    public function getDefaultAdapter()
    {
        return 'Dummy';
    }
}
