<?php

class DatabaseResultIteratorTest extends Chrome_TestCase
{
    public $_dataArray = array();

    public $_connection = null;

    protected function _getDatabaseFactory() {
        return $this->_appContext->getModelContext()->getDatabaseFactory();
    }


    public function testInitResultIterator() {

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');
        $db = $this->_getDatabaseFactory()->buildInterface('Simple', 'Iterator', $connection);
        $this->assertTrue(is_subclass_of($db->getResult(), 'Iterator'));
    }

    public function testFetchData() {

        $this->_dataArray = array(1, 2, 3, 4, 5, 6);

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        // Dummy_Adapter gets used via connection_dummy as default adapter
        $db = $this->_getDatabaseFactory()->buildInterface('Simple', 'Iterator', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_dataArray
        $db->getAdapter()->setDataResource($this);

        $i = 1;
        $this->assertSame($db->getResult()->isEmpty(), $db->getAdapter()->isEmpty());
        $this->assertFalse($db->getResult()->isEmpty());
        foreach($db->getResult() as $key => $value) {
            $this->assertEquals($i, $value);
            ++$i;
        }
        $this->assertSame(7, $i);
        $this->assertFalse($db->getResult()->isEmpty());
        $this->assertEquals(array(), $this->_dataArray);
    }

    public function testIteratorWithEmptyResult() {

        $this->_dataArray = array();

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        $db = $this->_getDatabaseFactory()->buildInterface('Simple', 'Iterator', $connection);

        $db->getAdapter()->setDataResource($this);

        foreach($db->getResult() as $key => $value) {
            $this->assertTrue(false, 'this assertion should not get executed');
        }

        $this->assertEquals(null, $db->getResult()->getNext());
    }

    public function testAffectedRows() {
        $db = $this->_getDatabaseFactory()->buildInterface('Simple', 'Iterator', null, 'Dummy');
        $db->getAdapter()->_affectedRows = 6;
        $this->assertEquals(6, $db->getResult()->getAffectedRows());
    }

    public function getNext() {
        return array_shift($this->_dataArray);
    }
}