<?php

require_once 'Tests/testsetup.php';

require_once LIB.'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';
require_once 'Tests/dummies/database/adapter.php';

class DatabaseFacadeTest extends PHPUnit_Framework_TestCase
{
    public function testFacade()
    {
        Chrome_Database_Registry_Connection::getInstance()->addConnection('facadeTest1', new Chrome_Database_Connection_Dummy('connection'));

        $db = Chrome_Database_Facade::getInterface('Simple', 'Assoc', 'facadeTest1');

        $this->assertEquals('Chrome_Database_Interface_Simple', get_class($db));
        $this->assertEquals('Chrome_Database_Result_Assoc', get_class($db->getResult()));
        $this->assertEquals('Chrome_Database_Connection_Dummy', get_class($db->getAdapter()->getConnection()));
        $this->assertEquals('Chrome_Database_Adapter_Dummy', get_class($db->getAdapter()));
    }

    public function testFacadeThrowExceptionsOnWrongInterface()
    {
        $this->setExpectedException('Chrome_Exception');

        $db = Chrome_Database_Facade::getInterface('ThisInterfaceDoesNotExist', 'Assoc');
    }

    public function testFacadeThrowExceptionsOnWrongResult()
    {
        $this->setExpectedException('Chrome_Exception');

        $db = Chrome_Database_Facade::getInterface('Simple', 'ThisResultShouldNotExist');
    }

    public function testFacadeThrowExceptionsOnWrongConnection()
    {
        $this->setExpectedException('Chrome_Exception_Database');

        $db = Chrome_Database_Facade::getInterface('Simple', 'Assoc', 'anyNotExistingConnection');
    }

    public function testFacadeThrowExceptionsOnWrongAdapter()
    {
        Chrome_Database_Registry_Connection::getInstance()->addConnection('facadeTest2', new Chrome_Database_Connection_Dummy('connection'));

        $this->setExpectedException('Chrome_Exception');

        $db = Chrome_Database_Facade::getInterface('Simple', 'Assoc', 'facadeTest2', 'anyNotExistingAdapter');
    }

    public function testFacadeWithDecoratorResults() {

        Chrome_Database_Registry_Connection::getInstance()->addConnection('facadeTest3', new Chrome_Database_Connection_Dummy('connection'));

        $db = Chrome_Database_Facade::getInterface('Simple', array('Iterator', 'Assoc'), 'facadeTest1');

        $result = $db->getResult();
        $this->assertEquals('Chrome_Database_Result_Iterator', get_class($result));
        $this->assertEquals('Chrome_Database_Result_Assoc', get_class($result->getAdapter()));
    }

    public function testFacadeWithConnectionObject() {

        $connection = new Chrome_Database_Connection_Dummy('example resource');

        $db = Chrome_Database_Facade::getInterface('Simple', array('Iterator', 'Assoc'), $connection);

        $this->assertEquals($connection, $db->getAdapter()->getConnection());
    }

    public function testFacadeWithDefaultConnection() {

        $registry = Chrome_Database_Registry_Connection::getInstance();
        if($registry->isExisting(Chrome_Database_Facade::DEFAULT_CONNECTION)) {

            if(!$registry->isConnected(Chrome_Database_Facade::DEFAULT_CONNECTION)) {
                // if an exception is thrown, then the configuration is no done correctly
                $registry->getConnectionObject(Chrome_Database_Facade::DEFAULT_CONNECTION)->connect();
            }

            $db = Chrome_Database_Facade::getInterface('Simple', 'Assoc');

        } else {
            $this->assertTrue(false, 'There was no default configuration for a connection found. Please configure that in Front_Controller!');
        }
    }
}
