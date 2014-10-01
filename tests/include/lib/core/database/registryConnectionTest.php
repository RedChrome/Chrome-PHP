<?php

namespace Test\Chrome\Database\Registry;


require_once LIB . 'core/database/database.php';
require_once 'tests/dummies/database/connection/dummy.php';

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    public $registry = null;

    public function setUp()
    {
        $this->registry = new \Chrome\Database\Registry\Connection();
    }

    public function testGetInstance()
    {
        $this->assertNotNull($this->registry);
    }

    public function testAddConnection()
    {
        $this->registry->addConnection('DatabaseRegistryConnectionTest1', new \Test\Chrome\Database\Connection\Dummy());
    }

    public function testAddConnectionTwice()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->registry->addConnection('DatabaseRegistryConnectionTest2', new \Test\Chrome\Database\Connection\Dummy());
        $this->registry->addConnection('DatabaseRegistryConnectionTest2', new \Test\Chrome\Database\Connection\Dummy());
    }

    public function testGetConnection()
    {
        $exampleConnection = 'thisShouldBeAConnection';
        $obj = new \Test\Chrome\Database\Connection\Dummy($exampleConnection);
        $this->registry->addConnection('DatabaseRegistryConnectionTest3', $obj);

        $this->assertEquals($exampleConnection, $this->registry->getConnection('DatabaseRegistryConnectionTest3'));
        $this->assertEquals($obj, $this->registry->getConnectionObject('DatabaseRegistryConnectionTest3'));
    }

    public function testIsConnected()
    {

        $this->registry->addConnection('DatabaseRegistryConnectionTest4', new \Test\Chrome\Database\Connection\Dummy());

        $this->assertTrue($this->registry->isConnected('DatabaseRegistryConnectionTest4'));
    }

    public function testExceptionIfConnectionNotExist()
    {

        $this->setExpectedException('\Chrome\Exception');

        $this->registry->getConnection('DatabaseRegistryConnectionTest5');
    }

    public function testExceptionIfConnectionObjectNotExist()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->registry->getConnectionObject('DatabaseRegistryConnectionTest6');
    }

    public function testExceptionIfConnectionIsNotEstablished()
    {
        $connection = new \Test\Chrome\Database\Connection\Dummy();
        $connection->setIsConnected(false);

        $this->registry->addConnection('DatabaseRegistryConnectionTest7', $connection);

        $this->setExpectedException('\Chrome\Exception\Database');

        $this->registry->getConnection('DatabaseRegistryConnectionTest7');
    }
}
