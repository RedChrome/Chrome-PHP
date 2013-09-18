<?php
class DatabaseAdapterCacheTest extends Chrome_TestCase
{
    protected $_data = array();

    public function getNext()
    {
        return array_shift($this->_data);
    }

    protected function _getDatabaseFactory()
    {
        return $this->_appContext->getModelContext()->getDatabaseFactory();
    }

    protected function _createDb()
    {
        $connection = new Chrome_Database_Connection_Dummy('exampleResource, not null');

        // Dummy_Adapter gets used via connection_dummy as default adapter
        $db = $this->_getDatabaseFactory()->buildInterface('Simple', 'Iterator', $connection);

        // this will force the adapter to access this class using method getNext()
        // and this will access the $_data
        $db->getAdapter()->setDataResource($this);

        return $db;
    }

    public function testSerialize()
    {
        $this->_data = array('asd', 1, 2.3, null, 'bla');
        $data = $this->_data;

        $db = $this->_createDb();

        $this->assertTrue($db->getAdapter() instanceof Chrome_Database_Adapter_Dummy);

        $db->getAdapter()->_affectedRows = 3;
        // get one element, to see whether it works
        $db->getResult()->getNext();

        // cache can get applied multiple times
        for($j = 0; $j < 5; ++$j)
        {

            $cacheAdapter = new Chrome_Database_Adapter_Cache($db->getResult());
            $this->assertFalse($cacheAdapter->isEmpty());
            $this->assertEquals(3, $cacheAdapter->getAffectedRows());
            $serialized = serialize($cacheAdapter);
            $unserializedAdapter = unserialize($serialized);

            $this->assertEquals(3, $unserializedAdapter->getAffectedRows());

            for($i = 0; $i < 5; ++$i)
            {
                $result = $db->getResult()->getNext();
                $this->assertEquals($data[$i], $result);
                // the 4.th data element is null. so all values after this one should be null too
                if($i <= 3)
                {
                    $this->assertEquals($result, $unserializedAdapter->getNext(), 'getNext did not match in ' . $j . '.th loop');
                } else
                {
                    $this->assertEquals(null, $unserializedAdapter->getNext(), 'got one "null" as data, that indicates the end of the result stream. no other values should be after that');
                }
                $this->assertEquals($db->getAdapter()->getAffectedRows(), $unserializedAdapter->getAffectedRows());
            }
        }
    }

    public function testClear()
    {
        $cacheAdapter = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $cacheAdapter2 = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $cacheAdapter2->clear();
        $this->assertEquals($cacheAdapter, $cacheAdapter2);
    }

    public function testIsEmpty()
    {
        $this->_data = array();

        $db = $this->_createDb();

        $cacheAdapter = new Chrome_Database_Adapter_Cache($db->getResult());
        $this->assertTrue($cacheAdapter->isEmpty());
    }

    public function testQuery()
    {
        $cacheAdapter = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $this->setExpectedException('Chrome_Exception');
        $cacheAdapter->query(null);
    }

    public function testEscape()
    {
        $cacheAdapter = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $this->setExpectedException('Chrome_Exception');
        $cacheAdapter->escape('Data to escape');
    }

    public function testSetConnection()
    {
        $cacheAdapter = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $this->setExpectedException('Chrome_Exception');
        $cacheAdapter->setConnection(new Chrome_Database_Connection_Dummy('not null'));
    }

    public function testGetConnection()
    {
        $cacheAdapter = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $this->setExpectedException('Chrome_Exception');
        $cacheAdapter->getConnection();
    }

    public function testGetErrorMessage()
    {
        $cacheAdapter = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $this->setExpectedException('Chrome_Exception');
        $cacheAdapter->getErrorMessage();
    }

    public function testGetErrorCode()
    {
        $cacheAdapter = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $this->setExpectedException('Chrome_Exception');
        $cacheAdapter->getErrorCode();
    }

    public function testGetLastInsertId()
    {
        $cacheAdapter = new Chrome_Database_Adapter_Cache($this->_createDb()->getResult());
        $this->setExpectedException('Chrome_Exception');
        $cacheAdapter->getLastInsertId();
    }
}
