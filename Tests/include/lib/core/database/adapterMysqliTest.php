<?php

require_once 'Tests/testsetup.php';

require_once 'Tests/dummies/database/connection/dummy.php';
require_once LIB . 'core/database/database.php';
require_once LIB . 'core/database/connection/mysqli.php';


class DatabaseAdapterMysqliTest extends DatabaseAdapterMysqlTest
{
    public function setUp()
    {
        try {
            $this->_db = Chrome_Database_Facade::getInterface('simple', 'assoc', 'mysqli_test');
        }
        catch (Chrome_Exception $e) {
            $this->_db = null;
        }
    }

    public function testSetConnectionWithWrongConnection() {

        $dummy = new Chrome_Database_Connection_Dummy('anytring but no mysqli connection');

        $this->setExpectedException('Chrome_Exception_Database');

        $this->_db->getAdapter()->setConnection($dummy);
    }

    public function testTwoQueriesAtOnce() {

        $result = $this->_db->query('SELECT * FROM cpp_require LIMIT 0,1');
        $this->_db->clear();
        $result2 = $this->_db->query('SELECT * FROM cpp_class LIMIT 0,1');

    }
}
