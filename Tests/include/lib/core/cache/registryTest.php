<?php

class CacheRegistryTest extends AbstractRegistryTestCase
{
    public function setUp()
    {
        $this->_setData = array(
            array('1.st Key' => new Chrome_Cache_Factory()),
            array('2.st Key' => new Chrome_Cache_Factory()),
            array('asd' => new Chrome_Cache_Factory()),
            array(1 => new Chrome_Cache_Factory()),
        );

        $this->_removeData = array('asd', 1);
    }

    protected function _createRegistry()
    {
        return new \Chrome\Registry\Cache\Factory\Registry();
    }
}