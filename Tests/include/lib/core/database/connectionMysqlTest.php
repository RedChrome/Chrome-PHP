<?php

require_once 'Tests/testsetup.php';

require_once LIB.'core/database_new/database.php';
require_once LIB.'core/database_new/connection/mysql.php';

class DatabaseConnectionMysqlTest extends PHPUnit_Framework_TestCase
{
    public function testThrowExceptionWhenCreatingConnectionWithoutAnyData() {

        $this->setExpectedException('Chrome_Exception');

        $connection = new Chrome_Database_Connection_Mysql();

        $connection->connect();
    }

    public function testCannotConnectToNotExistingServer()
    {
        $connection = new Chrome_Database_Connection_Mysql();
        $connection->setConnectionOptions('notExistingHost', 'guest', '', 'chrome_2');

        $this->setExpectedException('Chrome_Exception_Database');
        $connection->connect();
    }

    public function testCannotConnectWithInvalidUserNameOrPassword() {

        $connection = new Chrome_Database_Connection_Mysql();
        $connection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, '', MYSQL_DB);

        $this->setExpectedException('Chrome_Exception_Database');
        $connection->connect();
    }

    public function testConnect() {

        $connection = new Chrome_Database_Connection_Mysql();
        $connection->setConnectionOptions(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);


        $connection->connect();
    }


}
