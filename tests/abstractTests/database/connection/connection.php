<?php

abstract class AbstractDatabaseConnectionTestCase extends PHPUnit_Framework_TestCase
{
    protected $_databaseConnectionOptions = array('HOST' => MYSQL_HOST, 'USER' => MYSQL_USER, 'PASS' => MYSQL_PASS, 'DB' => MYSQL_DB);

    protected $_connection;

    abstract protected function _getDatabaseConnection();

    public function setUp() {
        $this->_connection = $this->_getDatabaseConnection();#new \Chrome\Database\Connection\Mysql();
    }

    public function testThrowExceptionWhenCreatingConnectionWithoutAnyData()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->_connection->connect();
    }

    public function doSkipTestsIfNeeded()
    {
        if(TEST_DATABASE_CONNECTIONS === false OR _skipDatabaseTest(get_class($this))) {
            $this->markTestSkipped();
        }
    }

    public function testCannotConnectToNotExistingServer()
    {
        $this->doSkipTestsIfNeeded();

        $this->_connection->setConnectionOptions('notExistingHost', 'guest', '', 'chrome_2');

        $this->setExpectedException('\Chrome\DatabaseException');
        $this->_connection->connect();
    }

    public function testCannotConnectWithInvalidUserNameOrPassword()
    {
        $this->doSkipTestsIfNeeded();

        $this->_connection->setConnectionOptions($this->_databaseConnectionOptions['HOST'], $this->_databaseConnectionOptions['USER'], 'anythingWRONG', $this->_databaseConnectionOptions['DB']);

        $this->setExpectedException('\Chrome\DatabaseException');
        $this->_connection->connect();
    }

    public function testConnect()
    {
        $this->doSkipTestsIfNeeded();

        $this->_connection->setConnectionOptions($this->_databaseConnectionOptions['HOST'], $this->_databaseConnectionOptions['USER'], $this->_databaseConnectionOptions['PASS'], $this->_databaseConnectionOptions['DB']);

        $this->_connection->connect();

        $this->assertTrue($this->_connection->isConnected());
        $this->assertTrue($this->_connection->connect());
    }

    public function testIsConnectedOnEmptyConnection()
    {
        $this->assertFalse($this->_connection->isConnected());
    }

    public function testDisconnect()
    {
        $this->_connection->disconnect();
    }
}
