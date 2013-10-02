<?php

class ConfigModelTest extends Chrome_TestCase
{
    protected $_config = null;

    protected function setUp()
    {
        $this->_config = new Chrome_Model_Config_Database($this->_appContext->getModelContext());
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
        $db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('simple', 'assoc');
        $db->query('DELETE FROM cpp_config WHERE name=\'testName\' AND subclass=\'testSubclass\'');

        $this->_config->setConfig('testName', 'testSubclass', true);

        $config = $this->_config->loadConfig();

        $this->assertSame(true, $config['testSubclass']['testName']);

        // clean up the database
        $db = $this->_appContext->getModelContext()->getDatabaseFactory()->buildInterface('simple', 'assoc');
        $db->query('DELETE FROM cpp_config WHERE name=\'testName\' AND subclass=\'testSubclass\'');

        $this->_config->setConfig('testName', 'testSubclass', true, 'boolean');

        $config = $this->_config->loadConfig();

        $this->assertSame(true, $config['testSubclass']['testName']);
    }
}