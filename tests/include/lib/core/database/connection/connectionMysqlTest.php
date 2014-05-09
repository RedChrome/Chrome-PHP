<?php

namespace Test\Chrome\Database\Connection;

class DatabaseConnectionMysqlTest extends AbstractDatabaseConnectionTestCase
{
    public function _getDatabaseConnection()
    {
       return new \Chrome\Database\Connection\Mysql();
    }

    public function doSkipTestsIfNeeded()
    {
        parent::doSkipTestsIfNeeded();

        if(get_class($this) === 'DatabaseConnectionMysqlTest' AND version_compare(PHP_VERSION, '5.5.0') >= 0) {
            $this->markTestSkipped('Cannot execute mysql test with php version 5.5.0.');
        }
    }
}
