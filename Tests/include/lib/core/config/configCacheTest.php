<?php

class ConfigCacheTest extends Chrome_TestCase
{
    protected $_config = null;

    protected $_model  = null;

    protected $_cache  = null;

    protected function setUp()
    {
        $this->_cache = new Test_Chrome_Cache_Dummy();

        $this->_model = new Test_Chrome_Model_Dummy();

        $this->_config = new Chrome_Model_Config_Cache($this->_model);

        $this->_config->setCache($this->_cache);
    }

    public function testloadConfigCacheHit()
    {
        $this->_cache->data = array('config' => 'cache_hit');

        $this->assertSame(array(), $this->_model->arguments);

        $this->assertSame('cache_hit', $this->_config->loadConfig());
    }

    public function testLoadConfigCacheMiss()
    {
        $this->_model->data = array('loadConfig' => 'anyData');
        $config = $this->_config->loadConfig();
        $this->assertSame(array(), $this->_model->arguments['loadConfig'][0]);
        $this->assertSame($config, 'anyData');
        $this->assertSame(array('config' => 'anyData'), $this->_cache->data);
    }

    public function testSetConfig()
    {
        $this->_cache->data = array('config' => 'cache_data');
        $this->_config->setConfig('anyName', 'anySubclass', 'anyValue');

        $this->assertSame(array('anyName', 'anySubclass', 'anyValue', null, ''), $this->_model->arguments['setConfig'][0]);
        $this->assertSame(array(), $this->_cache->data);
    }
}