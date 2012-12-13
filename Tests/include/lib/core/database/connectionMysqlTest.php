<?php

require_once 'Tests/testsetup.php';

require_once LIB . 'core/database/database.php';
require_once LIB . 'core/database/connection/mysql.php';

class DatabaseConnectionMysqlTest extends PHPUnit_Framework_TestCase
{
    public function testThrowExceptionWhenCreatingConnectionWithoutAnyData()
    {
        $this->setExpectedException('Chrome_Exception');

        $connection = new Chrome_Database_Connection_Mysql();

        $connection->connect();
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

        $connection = new Chrome_Database_Connection_Mysql();
        $connection->setConnectionOptions('notExistingHost', 'guest', '', 'chrome_2');

        $this->setExpectedException('Chrome_Exception_Database');
        $connection->connect();
    }

    public function testCannotConnectWithInvalidUserNameOrPassword()
    {
        $this->doSkipTestsIfNeeded();

        $connection = new Chrome_Database_Connection_Mysql();
        $connection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, 'anythingWRONG', MYSQL_DB);

        $this->setExpectedException('Chrome_Exception_Database');
        $connection->connect();
    }

    public function testConnect()
    {
        $this->doSkipTestsIfNeeded();

        $connection = new Chrome_Database_Connection_Mysql();
        $connection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);

        $connection->connect();

        $this->assertTrue($connection->isConnected());
        $this->assertTrue($connection->connect());

        Chrome_Database_Registry_Connection::getInstance()->addConnection('mysql_test', $connection, true);
    }

    public function testIsConnectedOnEmptyConnection()
    {
        $connection = new Chrome_Database_Connection_Mysql();
        $this->assertFalse($connection->isConnected());
    }

    public function testDisconnect()
    {
        $connection = new Chrome_Database_Connection_Mysql();
        $connection->disconnect();
    }
}
