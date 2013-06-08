<?php

;

require_once LIB . 'core/database/database.php';
require_once LIB . 'core/database/connection/mysql.php';

class DatabaseConnectionMysqlTest extends PHPUnit_Framework_TestCase
{
    protected $_connection;

    public function setUp() {
        $this->_connection = new Chrome_Database_Connection_Mysql();
    }

    public function testThrowExceptionWhenCreatingConnectionWithoutAnyData()
    {
        $this->setExpectedException('Chrome_Exception');

        $this->_connection->connect();
    }

    public function doSkipTestsIfNeeded()
    {
        if(TEST_DATABASE_CONNECTIONS === false) {
            $this->markTestSkipped();
        }
    }

    public function testCannotConnectToNotExistingServer()
    {
        $this->doSkipTestsIfNeeded();

        $this->_connection->setConnectionOptions('notExistingHost', 'guest', '', 'chrome_2');

        $this->setExpectedException('Chrome_Exception_Database');
        $this->_connection->connect();
    }

    public function testCannotConnectWithInvalidUserNameOrPassword()
    {
        $this->doSkipTestsIfNeeded();

        $this->_connection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, 'anythingWRONG', MYSQL_DB);

        $this->setExpectedException('Chrome_Exception_Database');
        $this->_connection->connect();
    }

    public function testConnect()
    {
        $this->doSkipTestsIfNeeded();

        $this->_connection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);

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
