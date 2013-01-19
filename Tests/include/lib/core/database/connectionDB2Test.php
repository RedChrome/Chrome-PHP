<?php

require_once 'Tests/testsetup.php';

require_once 'Tests/include/lib/core/database/connectionMysqlTest.php';

require_once LIB.'core/database/database.php';
require_once LIB.'core/database/connection/db2.php';

class DatabaseConnectionDB2Test extends DatabaseConnectionMysqlTest
{
    public function setUp()
    {
        $this->_connection = new Chrome_Database_Connection_DB2();
    }
}
