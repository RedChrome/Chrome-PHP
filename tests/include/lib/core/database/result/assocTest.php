<?php

namespace Test\Chrome\Database\Result;

require_once LIB.'core/database/database.php';
require_once 'tests/dummies/database/connection/dummy.php';
require_once 'tests/dummies/database/adapter.php';

class AssocTest extends \Test\Chrome\TestCase
{
    public $dataArray = array();

    protected function _getDatabaseFactory()
    {
        return $this->_appContext->getModelContext()->getDatabaseFactory();
    }

    public function testHasNext()
    {
        $this->dataArray = array(1,2);

        $connection = new \Test\Chrome\Database\Connection\Dummy('exampleResource, not null');

        // Dummy_Adapter gets used via connection_dummy as default adapter
        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Assoc', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_dataArray
        $db->getAdapter()->setDataResource($this);

        $this->assertTrue($db->getResult()->hasNext());
        $this->assertEquals(1, $db->getResult()->getNext());

        $this->dataArray = array();

        $db->getAdapter()->setDataResource($this);

        $this->assertFalse($db->getResult()->hasNext());
    }

    public function testGetAffectedRows()
    {
        $connection = new \Test\Chrome\Database\Connection\Dummy('exampleResource, not null');

        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Assoc', $connection);

        $db->getAdapter()->_affectedRows = 5;
        $this->assertEquals(5, $db->getResult()->getAffectedRows());

    }

    public function getNext()
    {
        return array_shift($this->dataArray);
    }

    public function testArrayAccessInterface()
    {
        $this->dataArray = array(array('testKey' => 'testValue'));

        $connection = new \Test\Chrome\Database\Connection\Dummy('exampleResource, not null');

        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Assoc', $connection);

        $db->getAdapter()->setDataResource($this);

        $result = $db->getResult();
        $result->getNext();

        $this->assertTrue($result->offsetExists('testKey'));
        $this->assertFalse($result->offsetExists('notExisting'));

        $this->assertEquals('testValue', $result->offsetGet('testKey'));

        $result->offsetSet('testKey', 'testValue2');
        $result->offsetSet('anyKey', true);
        $this->assertEquals('testValue2', $result->offsetGet('testKey'));
        $this->assertEquals(true, $result->offsetGet('anyKey'));

        $result->offsetUnset('anyKey');
        $result->offsetUnset('notExisting');
        $this->assertFalse($result->offsetExists('anyKey'));
    }

    public function testGetAdapter()
    {
        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Assoc');
        $this->assertEquals($db->getAdapter(), $db->getResult()->getAdapter());
        $this->assertTrue($db->getResult()->getAdapter() instanceof \Chrome\Database\Adapter\Adapter_Interface);
    }

}
