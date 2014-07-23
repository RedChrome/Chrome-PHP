<?php

namespace Test\Chrome\Database\Result;

class IteratorTest extends \Test\Chrome\TestCase
{
    public $dataArray = array();
    public $connection = null;

    protected function _getDatabaseFactory()
    {
        return $this->_appContext->getModelContext()->getDatabaseFactory();
    }

    public function testInitResultIterator()
    {
        $connection = new \Test\Chrome\Database\Connection\Dummy('exampleResource, not null');
        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Iterator', $connection);
        $this->assertTrue(is_subclass_of($db->getResult(), 'Iterator'));
    }

    public function testFetchData()
    {
        $this->dataArray = array(1, 2, 3, 4, 5, 6);

        $connection = new \Test\Chrome\Database\Connection\Dummy('exampleResource, not null');

        // Dummy_Adapter gets used via connection_dummy as default adapter
        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Iterator', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_dataArray
        $db->getAdapter()->setDataResource($this);

        $i = 1;
        $this->assertSame($db->getResult()->isEmpty(), $db->getAdapter()->isEmpty());
        $this->assertFalse($db->getResult()->isEmpty());
        foreach($db->getResult() as $key => $value)
        {
            $this->assertEquals($i, $value);
            ++$i;
        }
        $this->assertSame(7, $i);
        $this->assertFalse($db->getResult()->isEmpty());
        $this->assertEquals(array(), $this->dataArray);
    }

    public function testIteratorWithEmptyResult()
    {
        $this->dataArray = array();

        $connection = new \Test\Chrome\Database\Connection\Dummy('exampleResource, not null');

        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Iterator', $connection);

        $db->getAdapter()->setDataResource($this);
        $this->assertFalse($db->getResult()->hasNext(), 'adapter has no elements, but hasNext indicates that there exist at least one element');

        foreach($db->getResult() as $key => $value)
        {
            $this->assertTrue(false, 'adapter has no elements, but at least one element is in result');
        }

        $this->assertEquals(null, $db->getResult()->getNext());
    }

    public function testAffectedRows()
    {
        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Iterator', null, '\Test\Chrome\Database\Adapter\Dummy');
        $db->getAdapter()->_affectedRows = 6;
        $this->assertEquals(6, $db->getResult()->getAffectedRows());
    }

    public function testHasNext()
    {
        $this->dataArray = array(1, 2, 3, 4, 5, 6);

        $connection = new \Test\Chrome\Database\Connection\Dummy('exampleResource, not null');

        // Dummy_Adapter gets used via connection_dummy as default adapter
        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Iterator', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_dataArray
        $db->getAdapter()->setDataResource($this);
        $result = $db->getResult();
        // this assures that a rewind before the first hasNext has no effect.
        $result->rewind();
        $result->rewind();
        $result->rewind();
        $i = 0;
        $this->assertFalse($result->isEmpty());
        while($result->hasNext() === true)
        {
            $element = $result->getNext();
            $this->assertNotEquals(null, $element);
            ++$i;

            if($i >= 7) {
                $this->assertTrue(false, 'iterated over more than six elements, but adapter only contains six elements...');
            }

        }

        $this->assertEquals(6, $i, 'adapter contains exactly 6 elements, result set have to contain 6 elements too');

        $result->rewind();
        $i = 0;
        $this->assertFalse($result->isEmpty());
        while($result->hasNext() === true)
        {
            $element = $result->getNext();

            $this->assertNotEquals(null, $element, 'getNext should only return null if there was no next element, but checked in loop via hasNext()');

            ++$i;

            if($i >= 7) {
                $this->assertTrue(false, 'iterated over more than six elements, but adapter only contains six elements...');
            }
        }

        $this->assertEquals(6, $i, 'adapter contains exactly 6 elements, loop should iterate over exactly 6 elements. getNext does not return the very first element!');
    }

    public function getNext()
    {
        return array_shift($this->dataArray);
    }

    public function testSerialize()
    {
        $this->dataArray = array(1, 2, 3, 4, 5, 6);

        $connection = new \Test\Chrome\Database\Connection\Dummy('exampleResource, not null');

        // Dummy_Adapter gets used via connection_dummy as default adapter
        $db = $this->_getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Iterator', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_dataArray
        $db->getAdapter()->setDataResource($this);

        $result = $db->getResult();

        $resultSerialized = serialize($result);
        $resultUnserialized = unserialize($resultSerialized);

        $i = 0;
        while($resultUnserialized->hasNext() === true) {
            ++$i;
            $this->assertEquals($result->getNext(), $resultUnserialized->getNext());

            if($i >= 7) {
                $this->assertTrue(false, 'accessed more than 7 elements in unserialized result');
            }
        }

        $this->assertSame(6, $i);

    }
}