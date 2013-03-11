<?php

require_once 'Tests/testsetup.php';

require_once 'Tests/include/lib/core/database/connection/connectionMysqlTest.php';

require_once LIB.'core/database/database.php';
require_once LIB.'core/database/connection/db2.php';

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
