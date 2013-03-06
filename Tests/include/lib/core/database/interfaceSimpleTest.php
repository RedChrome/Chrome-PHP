<?php

require_once 'Tests/testsetup.php';

require_once LIB . 'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';
require_once 'Tests/dummies/database/adapter.php';
require_once 'Tests/dummies/database/result.php';

class DatabaseInterfaceSimpleTest extends PHPUnit_Framework_TestCase
{
    protected $_connection;
    protected $_adapter;
    protected $_result;

    protected $_interface;

    public function setUp()
    {
        $this->_connection = new Chrome_Database_Connection_Dummy('connection resource');

        $this->_adapter = new Chrome_Database_Adapter_Dummy($this->_connection);
        $this->_result = new Chrome_Database_Result_Dummy();
        $this->_result->setAdapter($this->_adapter);

        $this->_interface = new Chrome_Database_Interface_Simple($this->_adapter, $this->_result, new Chrome_Database_Registry_Statement());
    }

    public function testInterfaceReturnsAdapterAndResultCorrectly()
    {
        $this->assertSame($this->_adapter, $this->_interface->getAdapter());
        $this->assertSame($this->_result, $this->_interface->getResult());
    }

    public function testInterfaceReturnsNewResultOnClear()
    {
        $this->_interface->clear();

        $this->assertSame($this->_adapter->getConnection(), $this->_interface->getAdapter()->getConnection());
        $this->assertNotSame($this->_adapter, $this->_interface->getAdapter());
        $this->assertNotSame($this->_result, $this->_interface->getResult());
    }

    public function testGetStatement()
    {
        $query = 'this_is_an_example_statement';

        $this->_interface->query($query);

        $this->assertEquals($query, $this->_interface->getQuery());
    }

    public function testReplaceTablePrefix()
    {
        $statement = 'SELECT * from cpp_require LIMIT 0,1';
        $statement2 = 'SELECT * from ' . DB_PREFIX . '_require LIMIT 0,1';
        $this->_interface->query($statement);

        $this->assertEquals($statement2, $this->_interface->getQuery());
    }

    public function testReplaceParametersWithEscaping()
    {
        $table = 'requ\'ire';
        $tableEsc = 'requ\\\'ire';
        $limitEnde = 1;

        $statement = 'SELECT * from cpp_? LIMIT 0,?';
        $statement2 = 'SELECT * from ' . DB_PREFIX . '_' . $tableEsc . ' LIMIT 0,' . $limitEnde;
        $this->_interface->setParameters(array($table, $limitEnde));
        $this->_interface->query($statement);

        $this->assertEquals($statement2, $this->_interface->getQuery());
    }

    public function testReplaceParametersWithoutEscaping()
    {
        $table = 'requ\'ire';
        $tableEsc = 'requ\'ire';
        $limitEnde = 1;

        $statement = 'SELECT * from cpp_? LIMIT 0,?';
        $statement2 = 'SELECT * from ' . DB_PREFIX . '_' . $tableEsc . ' LIMIT 0,' . $limitEnde;
        $this->_interface->setParameters(array($table, $limitEnde), false);
        $this->_interface->query($statement);

        $this->assertEquals($statement2, $this->_interface->getQuery());
    }

    public function testSetParameterWithEscaping()
    {

        $table = 'requ\'ire';
        $tableEsc = 'requ\\\'ire';
        $limitEnde = 1;

        $statement = 'SELECT * from cpp_? LIMIT 0,?';
        $statement2 = 'SELECT * from ' . DB_PREFIX . '_' . $tableEsc . ' LIMIT 0,' . $limitEnde;
        $this->_interface->setParameter('table', $table, true);
        $this->_interface->setParameter('limitEnde', $limitEnde, true);
        $this->_interface->query($statement);

        $this->assertEquals($statement2, $this->_interface->getQuery());
        $this->assertEquals($statement, $this->_interface->getStatement());
    }

    public function testSetParameterWithoutEscaping()
    {

        $table = 'requ\'ire';
        $tableEsc = 'requ\'ire';
        $limitEnde = 1;

        $statement = 'SELECT * from cpp_? LIMIT 0,?';
        $statement2 = 'SELECT * from ' . DB_PREFIX . '_' . $tableEsc . ' LIMIT 0,' . $limitEnde;
        $this->_interface->setParameter('table', $table, false);
        $this->_interface->setParameter('limitEnde', $limitEnde, false);
        $this->_interface->query($statement);

        $this->assertEquals($statement2, $this->_interface->getQuery());
        $this->assertEquals($statement, $this->_interface->getStatement());
    }

    public function testGetStatementWhenNothingIsDone()
    {
        $this->assertNull($this->_interface->getStatement());
    }

    public function testExceptionIsThrownIfClearWasntCalled()
    {
        $this->_interface->query('Any query');

        $this->setExpectedException('Chrome_Exception_Database');

        $this->_interface->query('this should throw an exception');
    }

    public function testExceptionIsThrownIfNoQueryWasGiven()
    {
        $this->setExpectedException('Chrome_Exception_Database');

        $this->_interface->query(null);
    }

    public function testExceptionIsThrownIfEmptyQueryWasGiven()
    {
        $this->setExpectedException('Chrome_Exception_Database');

        $this->_interface->query('');
    }


}
