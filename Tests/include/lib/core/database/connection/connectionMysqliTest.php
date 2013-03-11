<?php

require_once 'Tests/testsetup.php';

require_once LIB . 'core/database/database.php';
require_once LIB . 'core/database/connection/mysqli.php';

class DatabaseConnectionMysqliTest extends DatabaseConnectionMysqlTest
{
    public function setUp()
    {
        $this->_connection = new Chrome_Database_Connection_Mysqli();
    }
}
