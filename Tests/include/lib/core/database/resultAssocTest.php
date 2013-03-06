<?php

require_once 'Tests/testsetup.php';

require_once LIB.'core/database/database.php';
require_once 'Tests/dummies/database/connection/dummy.php';
require_once 'Tests/dummies/database/adapter.php';

class DatabaseResultAssocTest extends PHPUnit_Framework_TestCase
{

    public $_dataArray = array();


    public function testHasNext() {

        $this->_dataArray = array(1,2);

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        // Dummy_Adapter gets used via connection_dummy as default adapter
        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', 'Assoc', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_dataArray
        $db->getAdapter()->setDataResource($this);

        $this->assertTrue($db->getResult()->hasNext());
        $this->assertEquals(1, $db->getResult()->getNext());

        $this->_dataArray = array();

        $db->getAdapter()->setDataResource($this);

        $this->assertFalse($db->getResult()->hasNext());
    }

    public function testGetAffectedRows()
    {
        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', 'Assoc', $connection);

        $db->getAdapter()->_affectedRows = 5;
        $this->assertEquals(5, $db->getResult()->getAffectedRows());

    }

    public function getNext() {
        return array_shift($this->_dataArray);
    }

    public function testArrayAccessInterface()
    {
        $this->_dataArray = array(array('testKey' => 'testValue'));

        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        $db = Chrome_Database_Facade::getFactory(TEST_FACTORY)->buildInterface('Simple', 'Assoc', $connection);

        $db->getAdapter()->setDataResource($this);

        $result = $db->getResult();
        $result->getNext();

        $this->assertTrue($result->offsetExists('testKey'));
        $this->assertFalse($result->offsetExists('notExisting'));

        $this->assertEquals('testValue',$result->offsetGet('testKey'));

        $result->offsetSet('testKey', 'testValue2');
        $result->offsetSet('anyKey', true);
        $this->assertEquals('testValue2', $result->offsetGet('testKey'));
        $this->assertEquals(true, $result->offsetGet('anyKey'));

        $result->offsetUnset('anyKey');
        $result->offsetUnset('notExisting');
        $this->assertFalse($result->offsetExists('anyKey'));
    }

}
