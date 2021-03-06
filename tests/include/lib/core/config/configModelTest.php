<?php

namespace Test\Chrome\Model\Config;

class ModelTest extends \Test\Chrome\TestCase
{
    protected $_config = null;

    protected function setUp()
    {
        $this->_config = new \Chrome\Model\Config\Database($this->_diContainer->get('\Chrome\Database\Factory\Factory_Interface'), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface'));
    }

    public function testLoadConfig()
    {
        $config = $this->_config->loadConfig();

        $this->assertTrue(is_array($config));
        $this->assertTrue(isset($config['testSubclass']));
        $this->assertTrue(isset($config['testSubclass']['testValueString']));
        $this->assertSame('testValue', $config['testSubclass']['testValueString']);
        $this->assertSame(42, $config['testSubclass']['testValueInt']);
        $this->assertSame(true, $config['testSubclass']['testValueBool']);
        $this->assertSame('any value, 1 1 ', $config['testSubclass']['testValueUnknown']);
        $this->assertSame(2.7182818, $config['testSubclass']['testValueDouble']);
    }

    public function testSetConfig()
    {
        // clean up the database
        $db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Assoc');
        $db->query('DELETE FROM cpp_config WHERE name=\'testName\' AND subclass=\'testSubclass\'');

        $this->_config->setConfig('testName', 'testSubclass', true);

        $config = $this->_config->loadConfig();

        $this->assertSame(true, $config['testSubclass']['testName']);

        // clean up the database
        $db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('\Chrome\Database\Facade\Simple', '\Chrome\Database\Result\Assoc');
        $db->query('DELETE FROM cpp_config WHERE name=\'testName\' AND subclass=\'testSubclass\'');

        $this->_config->setConfig('testName', 'testSubclass', true, 'boolean');

        $config = $this->_config->loadConfig();

        $this->assertSame(true, $config['testSubclass']['testName']);
    }
}