<?php

require_once 'Tests/testsetup.php';


class CacheFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testBuildNullCache() {

        // this adapter will always work
        $cacheAdapter = 'null';

        $factory = new Chrome_Cache_Factory();

        $cache = $factory->build($cacheAdapter, new Chrome_Cache_Option_Null());

        $this->assertTrue($cache instanceof Chrome_Cache_Interface);
        $this->assertTrue($cache instanceof Chrome_Cache_Null);
    }

    public function testBuildThrowsExceptionOnEmptyCacheAdapter() {

        $this->setExpectedException('Chrome_InvalidArgumentException');
        $cacheAdapter = '';
        $factory = new Chrome_Cache_Factory();
        $cache = $factory->build($cacheAdapter, new Chrome_Cache_Option_Null());

    }

     public function testBuildThrowsExceptionOnNullCacheAdapter() {

        $this->setExpectedException('Chrome_InvalidArgumentException');
        $cacheAdapter = null;
        $factory = new Chrome_Cache_Factory();
        $cache = $factory->build($cacheAdapter, new Chrome_Cache_Option_Null());
    }
}