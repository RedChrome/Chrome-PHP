<?php

class DatabaseConnectionMysqliTest extends AbstractDatabaseConnectionTestCase
{
    public function _getDatabaseConnection()
    {
        return new \Chrome\Database\Connection\Mysqli();
    }
}
