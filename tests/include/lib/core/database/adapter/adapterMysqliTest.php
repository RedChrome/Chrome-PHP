<?php

class DatabaseAdapterMysqliTest extends DatabaseAdapterMysqlTest
{

    public function setUp()
    {
        try {
            $this->_db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Assoc', 'mysqli_test');
        } catch(\Chrome\Exception $e)
        {
            $this->_db = null;
        }
    }

    public function testSetConnectionWithWrongConnection()
    {
        $this->doSkipTestsIfNeeded();

        $dummy = new \Test\Chrome\Database\Connection\Dummy('anytring but no mysqli connection');

        $this->setExpectedException('\Chrome\Exception\Database');

        $this->_db->getAdapter()->setConnection($dummy);
    }

    public function testTwoQueriesAtOnce()
    {
        $this->doSkipTestsIfNeeded();

        $result = $this->_db->query('SELECT * FROM cpp_autoload LIMIT 0,1');
        $this->_db->clear();
        $result2 = $this->_db->query('SELECT * FROM cpp_class LIMIT 0,1');
    }

    public function testSetConnectionAndConnectionIsNotEstablishedTheFirstTime()
    {
        $this->doSkipTestsIfNeeded();

        $connection = new \Test\Chrome\Database\Connection\Dummy();
        $connection->_isConnected = false;
        $connection->_connection = new mysqli();

        // call handleConnection on connect(), see parent class for handleConnection()
        $connection->_connectionHandler = $this;

        $this->_db->getAdapter()->setConnection($connection);
        $this->assertEquals($connection, $this->_db->getAdapter()->getConnection());
    }
}
