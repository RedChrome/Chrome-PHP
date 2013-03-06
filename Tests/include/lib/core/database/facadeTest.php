<?php

require_once 'Tests/testsetup.php';

require_once LIB . 'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';
require_once 'Tests/dummies/database/adapter.php';

class DatabaseFacadeTest extends PHPUnit_Framework_TestCase
{
    public function testDummy() {

    }

    /*
    protected $_defaultConnection;

    public function setUp()
    {
        $registry = Chrome_Database_Registry_Connection::getInstance();
        $this->_defaultConnection = $registry->getConnectionObject(Chrome_Database_Facade::getDefaultConnection());
    }

    public function tearDown()
    {
        $registry = Chrome_Database_Registry_Connection::getInstance();
        $registry->addConnection(Chrome_Database_Facade::getDefaultConnection(), $this->_defaultConnection, true);
    }

    public function testFacade()
    {
        Chrome_Database_Registry_Connection::getInstance()->addConnection('facadeTest1', new Chrome_Database_Connection_Dummy('connection'));

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', 'Assoc', 'facadeTest1');

        $this->assertEquals('Chrome_Database_Interface_Simple', get_class($db));
        $this->assertEquals('Chrome_Database_Result_Assoc', get_class($db->getResult()));
        $this->assertEquals('Chrome_Database_Connection_Dummy', get_class($db->getAdapter()->getConnection()));
        $this->assertEquals('Chrome_Database_Adapter_Dummy', get_class($db->getAdapter()));
    }

    public function testFacadeThrowExceptionsOnWrongInterface()
    {
        $this->setExpectedException('Chrome_Exception');

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('ThisInterfaceDoesNotExist', 'Assoc');
    }

    public function testFacadeThrowExceptionsOnWrongResult()
    {
        $this->setExpectedException('Chrome_Exception');

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', 'ThisResultShouldNotExist');
    }

    public function testFacadeThrowExceptionsOnWrongConnection()
    {
        $this->setExpectedException('Chrome_Exception_Database');

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', 'Assoc', 'anyNotExistingConnection');
    }

    public function testFacadeThrowExceptionsOnWrongAdapter()
    {
        Chrome_Database_Registry_Connection::getInstance()->addConnection('facadeTest2', new Chrome_Database_Connection_Dummy('connection'));

        $this->setExpectedException('Chrome_Exception');

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', 'Assoc', 'facadeTest2', 'anyNotExistingAdapter');
    }

    public function testFacadeWithDecoratorResults()
    {

        Chrome_Database_Registry_Connection::getInstance()->addConnection('facadeTest3', new Chrome_Database_Connection_Dummy('connection'));

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', array('Iterator', 'Assoc'), 'facadeTest1');

        $result = $db->getResult();
        $this->assertEquals('Chrome_Database_Result_Iterator', get_class($result));
        $this->assertEquals('Chrome_Database_Result_Assoc', get_class($result->getAdapter()));
    }

    public function testFacadeWithConnectionObject()
    {

        $connection = new Chrome_Database_Connection_Dummy('example resource');

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', array('Iterator', 'Assoc'), $connection);

        $this->assertEquals($connection, $db->getAdapter()->getConnection());
    }

    public function testFacadeThrowsExceptionOnNoDatabaseConnection()
    {
        $connection = new Chrome_Database_Connection_Dummy(null);

        $this->setExpectedException('Chrome_Exception_Database');

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', array('Iterator', 'Assoc'), $connection);

    }

    public function testFacadeWithDefaultConnection()
    {

        $registry = Chrome_Database_Registry_Connection::getInstance();
        if($registry->isExisting(Chrome_Database_Facade::DEFAULT_CONNECTION)) {

            if(!$registry->isConnected(Chrome_Database_Facade::DEFAULT_CONNECTION)) {
                // if an exception is thrown, then the configuration is not done correctly
                $registry->getConnectionObject(Chrome_Database_Facade::DEFAULT_CONNECTION)->connect();
            }

            $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', 'Assoc');

        } else {
            $this->assertTrue(false, 'There was no default configuration for a connection found. Please configure that in Front_Controller!');
        }
    }

    public function testFacadeInitCompositionWithConnectionWithoutDefaultComposition()
    {

        $registry = Chrome_Database_Registry_Connection::getInstance();
        $connection = new Chrome_Database_Connection_Dummy('example');
        $registry->addConnection('testFacadeInitCompositionWithConnection', $connection);

        $comp = new Chrome_Database_Composition('Simple', null, null, 'testFacadeInitCompositionWithConnection');

        $db = Chrome_Database_Facade::initComposition($comp, null);

        $this->assertNotNull($db);
        $this->assertTrue($db instanceof Chrome_Database_Interface_Simple);
        $this->assertSame($db->getAdapter()->getConnection(), $connection);
    }

    public function testFacadeInitCompositionWithEmptyComposition()
    {

        $comp = new Chrome_Database_Composition();

        $db = Chrome_Database_Facade::initComposition($comp);

        $this->assertNotNull($db);
        $this->assertTrue($db instanceof Chrome_Database_Interface_Interface);
    }

    public function testFacadeInitCompositionWithDefaultComposition()
    {

        $registry = Chrome_Database_Registry_Connection::getInstance();
        $connection = new Chrome_Database_Connection_Dummy('example');
        $registry->addConnection('testFacadeInitCompositionWithDefaultComposition', $connection);


        $comp = new Chrome_Database_Composition(null, 'dummy'); //this is empty, so we should only use $defComp

        $defComp = new Chrome_Database_Composition('model', 'assoc', 'dummy', 'testFacadeInitCompositionWithDefaultComposition');

        $db = Chrome_Database_Facade::initComposition($comp, $defComp);

        $this->assertNotNull($db);
        $this->assertTrue($db instanceof Chrome_Database_Interface_Model);
        $this->assertTrue($db->getResult() instanceof Chrome_Database_Result_Dummy);
        $this->assertTrue($db->getAdapter() instanceof Chrome_Database_Adapter_Dummy);
        $this->assertTrue($db->getAdapter()->getConnection() instanceof Chrome_Database_Connection_Dummy);
    }

    public function testFacadeTriesToConnectSuccessfully()
    {
        $registry = Chrome_Database_Registry_Connection::getInstance();
        $connection = new Chrome_Database_Connection_Dummy('example');
        $connection->setIsConnected(false);

        $registry->addConnection(Chrome_Database_Facade::getDefaultConnection(), $connection, true);

        $interface = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('model', 'assoc');
        $this->assertSame($connection, $interface->getAdapter()->getConnection());

    }

    public function testFacadeThrowsDatabaseExceptionOnConnect()
    {
        $registry = Chrome_Database_Registry_Connection::getInstance();
        $connection = new Chrome_Database_Connection_Dummy('example');
        $connection->setIsConnected(false);
        $connection->throwExceptionOnConnect('Chrome_Exception_Database');
        $registry->addConnection(Chrome_Database_Facade::getDefaultConnection(), $connection, true);

        $this->setExpectedException('Chrome_Exception_Database');
        $interface = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('model', 'assoc');
    }

    public function testFacadeThrowsExceptionOnConnect()
    {
        $registry = Chrome_Database_Registry_Connection::getInstance();
        $connection = new Chrome_Database_Connection_Dummy('example');
        $connection->setIsConnected(false);
        $connection->throwExceptionOnConnect('Chrome_Exception');
        $defConnection = $registry->getConnectionObject(Chrome_Database_Facade::getDefaultConnection());
        $registry->addConnection(Chrome_Database_Facade::getDefaultConnection(), $connection, true);

        $this->setExpectedException('Chrome_Exception');
        $interface = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('model', 'assoc');
    }

    public function testFacadeThrowsChromeExceptionOnConnect()
    {
        $registry = Chrome_Database_Registry_Connection::getInstance();
        $connection = new Chrome_Database_Connection_Dummy('example');
        $connection->setIsConnected(false);
        $connection->throwExceptionOnConnect('Chrome_Exception_Authentication');

        $registry->addConnection(Chrome_Database_Facade::getDefaultConnection(), $connection, true);

        $this->setExpectedException('Chrome_Exception'); // we expect only Chrome_Exceptions
        $interface = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('model', 'assoc', '');
    }

    public function testFacadeRequireClassConnection()
    {
        $this->setExpectedException('Chrome_Exception');

        Chrome_Database_Facade::requireClass('connection', 'notExistingClassSuffix');
    }

    public function testFacadeRequireClassWrongType()
    {
        $this->setExpectedException('Chrome_Exception');

        Chrome_Database_Facade::requireClass('notExistingType', 'anything');
    }

    public function testDefaultConnection() {

        $conn = Chrome_Database_Facade::getDefaultConnection();

        $testConnection = 'anyConnectionIdentifier';

        Chrome_Database_Facade::setDefaultConnection($testConnection);

        $this->assertEquals($testConnection, Chrome_Database_Facade::getDefaultConnection());

        Chrome_Database_Facade::setDefaultConnection($conn);
    }*/
}
