<?php

class DatabaseConnectionMysqliTest extends AbstractDatabaseConnectionTestCase
{
    public function _getDatabaseConnection()
    {
        return new Chrome_Database_Connection_Mysqli();
    }
}
