<?php

class DatabaseConnectionMysqliTest extends DatabaseConnectionMysqlTest
{
    public function setUp()
    {
        $this->_connection = new Chrome_Database_Connection_Mysqli();
    }
}
