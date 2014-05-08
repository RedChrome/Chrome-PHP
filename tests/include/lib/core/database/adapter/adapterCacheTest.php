<?php
class DatabaseAdapterCacheTest extends Chrome_TestCase
{
    protected $_rewindableInterface = '\Chrome\Database\Result\Rewindable_Interface';

    public function testGetAffectedRows()
    {
        $result = $this->getMock($this->_rewindableInterface);
        $result->expects($this->exactly(6))->method('hasNext')->will($this->onConsecutiveCalls(true, true, true, true, true, false));
        $result->expects($this->exactly(5))->method('getNext')->will($this->onConsecutiveCalls(3, 2, null, 0, '0'));
        $result->expects($this->exactly(2))->method('rewind');
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);

        $this->assertEquals(5, $cacheAdapter->getAffectedRows());

        $result = $this->getMock($this->_rewindableInterface);
        $result->expects($this->exactly(6))->method('hasNext')->will($this->onConsecutiveCalls(true, true, true, true, true, false));
        $result->expects($this->exactly(2))->method('rewind');
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);
        $this->assertEquals(5, $cacheAdapter->getAffectedRows());

        $result = $this->getMock($this->_rewindableInterface);
        $result->expects($this->exactly(1))->method('hasNext')->will($this->onConsecutiveCalls(false, true, true, true, true, false));
        $result->expects($this->exactly(2))->method('rewind');
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);
        $this->assertEquals(0, $cacheAdapter->getAffectedRows());
    }

    public function testSerialize()
    {
        $data = array(3, 2, null, 0, '0');

        $result = $this->getMock($this->_rewindableInterface);
        $result->expects($this->exactly(6))->method('hasNext')->will($this->onConsecutiveCalls(true, true, true, true, true, false));
        $result->expects($this->exactly(5))->method('getNext')->will($this->returnValues($data));
        $result->expects($this->exactly(2))->method('rewind');

        $cache = new \Chrome\Database\Adapter\Cache($result);
        $cacheSerialized = serialize($cache);
        $cacheUnserialized = unserialize($cacheSerialized);

        $this->assertEquals($cache->isEmpty(), $cacheUnserialized->isEmpty());
        $this->assertEquals($cache->getAffectedRows(), $cacheUnserialized->getAffectedRows());

        for($i=0; $i<count($data)+3; ++$i) {
            $getNext = $cacheUnserialized->getNext();
            $this->assertEquals($cache->getNext(), $getNext);

            if(isset($data[$i])) {
                $this->assertSame($data[$i], $getNext);
            }
        }
    }

    public function testClear()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $cacheAdapter2 = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $cacheAdapter2->clear();
        $this->assertEquals($cacheAdapter, $cacheAdapter2);
    }

    public function testIsEmpty()
    {
        $result = $this->getMock($this->_rewindableInterface);
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);
        $this->assertTrue($cacheAdapter->isEmpty());

        $result->expects($this->any())->method('hasNext')->will($this->onConsecutiveCalls(true, true, false));
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);
        $this->assertFalse($cacheAdapter->isEmpty());
    }

    public function testQuery()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->query(null);
    }

    public function testEscape()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->escape('Data to escape');
    }

    public function testSetConnection()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->setConnection($this->getMock('\Chrome\Database\Connection\Connection_Interface'));
    }

    public function testGetConnection()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->getConnection();
    }

    public function testGetErrorMessage()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->getErrorMessage();
    }

    public function testGetErrorCode()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->getErrorCode();
    }

    public function testGetLastInsertId()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->getMock($this->_rewindableInterface));
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->getLastInsertId();
    }
}
