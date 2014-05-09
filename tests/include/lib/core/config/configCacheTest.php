<?php

namespace Test\Chrome\Model\Config;

class CacheTest extends \Chrome_TestCase
{
    protected $_config = null;

    protected $_model  = null;

    protected $_cache  = null;

    protected function setUp()
    {
        $this->_cache = new \Chrome\Cache\Memory();

        $this->_model = new \Test\Chrome\Model\Dummy();

        $this->_config = new \Chrome\Model\Config\Cache($this->_model, $this->_cache);

        $this->_config->setCache($this->_cache);
    }

    public function testloadConfigCacheHit()
    {
        $this->_cache->set('config', 'cache_hit');

        $this->assertSame(array(), $this->_model->arguments);

        $this->assertSame('cache_hit', $this->_config->loadConfig());
    }

    public function testLoadConfigCacheMiss()
    {
        $this->_model->data = array('loadConfig' => 'anyData');
        $config = $this->_config->loadConfig();
        $this->assertSame(array(), $this->_model->arguments['loadConfig'][0]);
        $this->assertSame($config, 'anyData');
        $this->assertSame('anyData', $this->_cache->get('config'));
    }

    public function testSetConfig()
    {
        $this->_cache->set('config', 'cache_data');
        $this->_config->setConfig('anyName', 'anySubclass', 'anyValue');

        $this->assertSame(array('anyName', 'anySubclass', 'anyValue', null, '', 0), $this->_model->arguments['setConfig'][0]);
        $this->assertSame(null, $this->_cache->get('setConfig'));
        $this->assertSame(null, $this->_cache->get('anyName'));
        $this->assertSame(null, $this->_cache->get('anySubclass'));
    }
}