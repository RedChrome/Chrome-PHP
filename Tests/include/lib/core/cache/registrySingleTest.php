<?php

use Chrome\Registry\Cache\Factory\Registry_Single;

class CacheRegistrySingleTest extends AbstractRegistryTestCase
{
    public function setUp()
    {
        $this->_setData = array(array('1.st Key' => new Chrome_Cache_Factory()),
                                array('2.st Key' => new Chrome_Cache_Factory()),
                                array('asd' => new Chrome_Cache_Factory()),
                                array(1 => new Chrome_Cache_Factory()));

        $this->_removeData = array('asd', 1);
    }

    protected function _createRegistry()
    {
        return new \Chrome\Registry\Cache\Factory\Registry_Single();
    }

    public function testGetAll()
    {
        $registry = $this->_createRegistry();
        $this->_fillRegistry($registry);

        $this->assertEquals(array(Registry_Single::DEFAULT_FACTORY => $registry->get(Registry_Single::DEFAULT_OBJECT)), $registry->getAll());
    }
}