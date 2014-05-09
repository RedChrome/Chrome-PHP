<?php

require_once LIB . 'core/database/database.php';
require_once 'tests/dummies/database/connection/dummy.php';

class DatabaseRegistryConnectionTest extends PHPUnit_Framework_TestCase
{
    public $_registry = null;

    public function setUp()
    {
        $this->_registry = new Chrome\Database\Registry\Connection();
    }

    public function testGetInstance()
    {
        $this->assertNotNull($this->_registry);
    }

    public function testAddConnection()
    {
        $this->_registry->addConnection('DatabaseRegistryConnectionTest1', new \Test\Chrome\Database\Connection\Dummy());
    }

    public function testAddConnectionTwice()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->_registry->addConnection('DatabaseRegistryConnectionTest2', new \Test\Chrome\Database\Connection\Dummy());
        $this->_registry->addConnection('DatabaseRegistryConnectionTest2', new \Test\Chrome\Database\Connection\Dummy());
    }

    public function testGetConnection()
    {
        $exampleConnection = 'thisShouldBeAConnection';
        $obj = new \Test\Chrome\Database\Connection\Dummy($exampleConnection);
        $this->_registry->addConnection('DatabaseRegistryConnectionTest3', $obj);

        $this->assertEquals($exampleConnection, $this->_registry->getConnection('DatabaseRegistryConnectionTest3'));
        $this->assertEquals($obj, $this->_registry->getConnectionObject('DatabaseRegistryConnectionTest3'));
    }

    public function testIsConnected()
    {

        $this->_registry->addConnection('DatabaseRegistryConnectionTest4', new \Test\Chrome\Database\Connection\Dummy());

        $this->assertTrue($this->_registry->isConnected('DatabaseRegistryConnectionTest4'));
    }

    public function testExceptionIfConnectionNotExist()
    {

        $this->setExpectedException('\Chrome\Exception');

        $this->_registry->getConnection('DatabaseRegistryConnectionTest5');
    }

    public function testExceptionIfConnectionObjectNotExist()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->_registry->getConnectionObject('DatabaseRegistryConnectionTest6');
    }

    public function testExceptionIfConnectionIsNotEstablished()
    {
        $connection = new \Test\Chrome\Database\Connection\Dummy();
        $connection->setIsConnected(false);

        $this->_registry->addConnection('DatabaseRegistryConnectionTest7', $connection);

        $this->setExpectedException('\Chrome\DatabaseException');

        $this->_registry->getConnection('DatabaseRegistryConnectionTest7');
    }
}
