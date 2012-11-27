<?php

require_once 'Tests/testsetup.php';

require_once LIB . 'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';

class DatabaseRegistryConnectionTest extends PHPUnit_Framework_TestCase
{
    public $_registry = null;

    public function setUp()
    {
        $this->_registry = Chrome_Database_Registry_Connection::getInstance();
    }

    public function testGetInstance()
    {
        $this->assertNotNull($this->_registry);
    }

    public function testAddConnection()
    {
        $this->_registry->addConnection('DatabaseRegistryConnectionTest1', new Chrome_Database_Connection_Dummy());
    }

    public function testAddConnectionTwice()
    {
        $this->setExpectedException('Chrome_Exception');

        $this->_registry->addConnection('DatabaseRegistryConnectionTest2', new Chrome_Database_Connection_Dummy());
        $this->_registry->addConnection('DatabaseRegistryConnectionTest2', new Chrome_Database_Connection_Dummy());
    }

    public function testGetConnection()
    {
        $exampleConnection = 'thisShouldBeAConnection';
        $obj = new Chrome_Database_Connection_Dummy($exampleConnection);
        $this->_registry->addConnection('DatabaseRegistryConnectionTest3', $obj);

        $this->assertEquals($exampleConnection, $this->_registry->getConnection('DatabaseRegistryConnectionTest3'));
        $this->assertEquals($obj, $this->_registry->getConnectionObject('DatabaseRegistryConnectionTest3'));
    }

    public function testIsConnected()
    {

        $this->_registry->addConnection('DatabaseRegistryConnectionTest4', new Chrome_Database_Connection_Dummy());

        $this->assertTrue($this->_registry->isConnected('DatabaseRegistryConnectionTest4'));
    }

    public function testExceptionIfConnectionNotExist()
    {

        $this->setExpectedException('Chrome_Exception');

        $this->_registry->getConnection('DatabaseRegistryConnectionTest5');
    }

    public function testExceptionIfConnectionObjectNotExist()
    {

        $this->setExpectedException('Chrome_Exception');

        $this->_registry->getConnectionObject('DatabaseRegistryConnectionTest6');
    }
}
