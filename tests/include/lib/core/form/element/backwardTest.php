<?php

namespace Test\Chrome\Form\Element;

use Mockery as M;

class BackwardElementTest extends \PHPUnit_Framework_TestCase
{
    protected $_id = 'backward';

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\Form_Interface');
    }

    protected function _getElement($form, $option)
    {
        return new \Chrome\Form\Element\Backward($form, $this->_id, $option);
    }

    protected function _getBasicElementOption()
    {
        return M::mock('\Chrome\Form\Option\BasicElement_Interface');
    }

    protected function _getElementOption()
    {
        return M::mock('\Chrome\Form\Option\Element_Interface');
    }

    protected function _getMultipleElementOption()
    {
        return M::mock('\Chrome\Form\Option\MultipleElement_Interface');
    }

    public function testElementsLifecycle()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();

        $form->shouldReceive('getSentData')->andReturnNull();

        $element = $this->_getElement($form, $option);
        $ret = $element->create();

        $this->assertTrue($ret);

        $this->assertTrue($element->isCreated());
        $this->assertFalse($element->isSent());
        $this->assertTrue($element->isValid());
    }

    public function testElementIsSent()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();

        $form->shouldReceive('getSentData')->andReturn(array('backward' => ''));

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertTrue($element->isCreated());
        $this->assertTrue($element->isSent());
        $this->assertTrue($element->isValid());
        $this->assertArrayHasKey($this->_id, $element->getData());
    }

    public function testGetData()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();

        $faker = \Faker\Factory::create();
        $value = $faker->text;

        $form->shouldReceive('getSentData')->andReturn(array('backward' => $value));

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertArrayHasKey($this->_id, $element->getData());
        $this->assertContains($value, $element->getData());
    }
}