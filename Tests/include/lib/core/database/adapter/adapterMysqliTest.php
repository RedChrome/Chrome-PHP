<?php

class DatabaseAdapterMysqliTest extends DatabaseAdapterMysqlTest
{

    public function setUp()
    {
        $this->_db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('simple', 'assoc', 'mysqli_test');
    }

    public function testSetConnectionWithWrongConnection()
    {
        $dummy = new Chrome_Database_Connection_Dummy('anytring but no mysqli connection');

        $this->setExpectedException('Chrome_Exception_Database');

        $this->_db->getAdapter()->setConnection($dummy);
    }

    public function testTwoQueriesAtOnce()
    {
        $result = $this->_db->query('SELECT * FROM cpp_require LIMIT 0,1');
        $this->_db->clear();
        $result2 = $this->_db->query('SELECT * FROM cpp_class LIMIT 0,1');
    }

    public function testSetConnectionAndConnectionIsNotEstablishedTheFirstTime()
    {
        $connection = new Chrome_Database_Connection_Dummy();
        $connection->_isConnected = false;
        $connection->_connection = new mysqli();

        // call handleConnection on connect(), see parent class for handleConnection()
        $connection->_connectionHandler = $this;

        $this->_db->getAdapter()->setConnection($connection);
        $this->assertEquals($connection, $this->_db->getAdapter()->getConnection());
    }
}
