<?php

require_once 'connectionMysqlTest.php';

class DatabaseConnectionDB2Test extends DatabaseConnectionMysqlTest
{
    public function setUp()
    {
        $this->_connection = new Chrome_Database_Connection_DB2();
    }

    public function doSkipTestsIfNeeded()
    {
        if(TEST_DATABASE_CONNECTIONS === false || !extension_loaded('db2')) {
            $this->markTestSkipped('Extension DB2 not loaded. Cannot test');
        }
    }
}
