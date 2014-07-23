<?php

namespace Test\Chrome\Database\Adapter;

use Mockery as M;

class CacheTest extends \Test\Chrome\TestCase
{
    protected function _getRewindableResultMock()
    {
        return M::mock('\Chrome\Database\Result\Rewindable_Interface')->shouldIgnoreMissing(null);
    }

    public function testGetAffectedRows()
    {
        $result = $this->_getRewindableResultMock();

        $result->shouldReceive('hasNext')->times(6)->withNoArgs()->andReturn(true, true, true, true, true, false);
        $result->shouldReceive('getNext')->times(5)->withNoArgs()->andReturn(3, 2, null, 0, '0');
        $result->shouldReceive('rewind')->times(2);

        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);

        $this->assertEquals(5, $cacheAdapter->getAffectedRows());

        $result = $this->_getRewindableResultMock();
        $result->shouldReceive('hasNext')->times(6)->withNoArgs()->andReturn(true, true, true, true, true, false);
        $result->shouldReceive('rewind')->times(2)->withNoArgs();

        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);
        $this->assertEquals(5, $cacheAdapter->getAffectedRows());

        $result = $this->_getRewindableResultMock();
        $result->shouldReceive('hasNext')->withNoArgs()->times(1)->andReturn(false, true, true, true, true, false);
        $result->shouldReceive('rewind')->withNoArgs()->times(2);
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);
        $this->assertEquals(0, $cacheAdapter->getAffectedRows());
    }

    public function testSerialize()
    {
        $data = array(3, 2, null, 0, '0');

        $result = $this->_getRewindableResultMock();

        $result->shouldReceive('hasNext')->times(6)->withNoArgs()->andReturn(true, true, true, true, true, false);
        $result->shouldReceive('getNext')->times(5)->withNoArgs()->andReturnValues($data);
        $result->shouldReceive('rewind')->times(2);

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
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $cacheAdapter2 = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $cacheAdapter2->clear();
        $this->assertEquals($cacheAdapter, $cacheAdapter2);
    }

    public function testIsEmpty()
    {
        $result = $this->_getRewindableResultMock();
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);
        $this->assertTrue($cacheAdapter->isEmpty());

        $result->shouldReceive('hasNext')->zeroOrMoreTimes()->withNoArgs()->andReturn(true, true, false);
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($result);
        $this->assertFalse($cacheAdapter->isEmpty());
    }

    public function testQuery()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->query(null);
    }

    public function testEscape()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->escape('Data to escape');
    }

    public function testSetConnection()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->setConnection(M::mock('\Chrome\Database\Connection\Connection_Interface'));
    }

    public function testGetConnection()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->getConnection();
    }

    public function testGetErrorMessage()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->getErrorMessage();
    }

    public function testGetErrorCode()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->getErrorCode();
    }

    public function testGetLastInsertId()
    {
        $cacheAdapter = new \Chrome\Database\Adapter\Cache($this->_getRewindableResultMock());
        $this->setExpectedException('\Chrome\Exception');
        $cacheAdapter->getLastInsertId();
    }
}
