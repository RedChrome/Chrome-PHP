<?php

class ConfigTest extends Chrome_TestCase
{
    protected $_config = null;

    protected $_model  = null;

    protected function setUp()
    {
        $this->_model = new \Test\Chrome\Model\Dummy();

        $this->_model->data = array('loadConfig' => array('subclass' => array('name1' => 'value1', 'name2' => 'value2')));

        $this->_config = new \Chrome\Config\Config($this->_model);
    }

    public function testGetModel()
    {
        $this->assertSame($this->_model, $this->_config->getModel());
    }

    public function testGetConfig()
    {
        $this->assertSame(array('name1' => 'value1', 'name2' => 'value2'), $this->_config->getConfig('subclass'));
        $this->assertSame('value1', $this->_config->getConfig('subclass', 'name1'));
    }

    public function testGetConfigThrowsException()
    {
        $this->setExpectedException('Chrome_Exception');

        $this->_config->getConfig('notExistingSubClass');
    }

    public function testGetConfigThrowsException2()
    {
        $this->setExpectedException('Chrome_Exception');

        $this->_config->getConfig('subclass', 'notExistingName');
    }
}