<?php

require_once 'Tests/testsetup.php';

require_once LIB.'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';
require_once 'Tests/dummies/database/adapter.php';

class DatabaseResultInteratorTest extends Chrome_TestCase
{
    public $_dataArray = array();

    public $_connection = null;

    public function testInitResultIterator() {

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');
        $db = $this->_appContext->getDatabaseFactory()->buildInterface('Simple', 'Iterator', $connection);
        $this->assertTrue(is_subclass_of($db->getResult(), 'Iterator'));
    }

    public function testFetchData() {

        $this->_dataArray = array(1, 2, 3, 4, 5, 6);

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        // Dummy_Adapter gets used via connection_dummy as default adapter
        $db = $this->_appContext->getDatabaseFactory()->buildInterface('Simple', 'Iterator', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_dataArray
        $db->getAdapter()->setDataResource($this);

        $i = 1;
        foreach($db->getResult() as $key => $value) {
            $this->assertEquals($i, $value);
            $this->assertEquals($db->getResult()->valid(), $db->getResult()->hasNext());
            ++$i;
        }
        $this->assertFalse($db->getResult()->isEmpty());
        $this->assertEquals(array(),$this->_dataArray);
    }

    public function testIteratorWithEmptyResult() {

        $this->_dataArray = array();

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        $db = $this->_appContext->getDatabaseFactory()->buildInterface('Simple', 'Iterator', $connection);

        $db->getAdapter()->setDataResource($this);

        foreach($db->getResult() as $key => $value) {
            $this->assertTrue(false, 'this assertion should not get executed');
        }

        $this->assertEquals(null, $db->getResult()->getNext());
    }

    public function testAffectedRows() {
        $db = $this->_appContext->getDatabaseFactory()->buildInterface('Simple', 'Iterator', null, 'Dummy');
        $db->getAdapter()->_affectedRows = 6;
        $this->assertEquals(6, $db->getResult()->getAffectedRows());
    }

    public function getNext() {
        return array_shift($this->_dataArray);
    }



}
