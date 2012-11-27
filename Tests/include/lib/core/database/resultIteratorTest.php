<?php

require_once 'Tests/testsetup.php';

require_once LIB.'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';
require_once 'Tests/dummies/database/adapter.php';

class DatabaseResultInterfaceTest extends PHPUnit_Framework_TestCase
{
    public $_dataArray = array();

    public $_connection = null;

    public function testInitResultIterator() {

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');
        $db = Chrome_Database_Facade::getInterface('Simple', 'Iterator', $connection);
        $this->assertTrue(is_subclass_of($db->getResult(), 'Iterator'));
    }

    public function testFetchData() {

        $this->_dataArray = array(1, 2, 3, 4, 5, 6);

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        $db = Chrome_Database_Facade::getInterface('Simple', 'Iterator', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_dataArray
        $db->getAdapter()->setDataResource($this);

        $i = 1;
        foreach($db->getResult() as $key => $value) {
            $this->assertEquals($i, $value);
            ++$i;
        }

        $this->assertEquals(array(),$this->_dataArray);
    }

    public function testIteratorWithEmptyResult() {

        $this->_dataArray = array();

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        $db = Chrome_Database_Facade::getInterface('Simple', 'Iterator', $connection);

        $db->getAdapter()->setDataResource($this);

        foreach($db->getResult() as $key => $value) {
            $this->assertTrue(false, 'this assertion should not get executed');
        }

        $this->assertEquals(null, $db->getResult()->getNext());
    }

    public function getNext() {
        return array_shift($this->_dataArray);
    }
}
