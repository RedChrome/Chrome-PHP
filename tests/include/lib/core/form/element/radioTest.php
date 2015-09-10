<?php

namespace Test\Chrome\Form\Element;

use Mockery as M;

class RadioElementTest extends \PHPUnit_Framework_TestCase
{
    protected $_id = 'radio';

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\Form_Interface');
    }

    protected function _getElement($form, $option)
    {
        return new \Chrome\Form\Element\Radio($form, $this->_id, $option);
    }

    protected function _getElementOption()
    {
        return new \Chrome\Form\Option\MultipleElement();
    }

    public function testCreate()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();

        $form->shouldReceive('getSentData')->andReturnNull();
        $option->setRequired(array('key'));

        $element = $this->_getElement($form, $option);
        $element->create();
        $this->assertTrue($element->isCreated());
        $this->assertFalse($element->isSent());
        $this->assertNull($element->getData());
    }

    public function testSent()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();
        $option->setRequired(array());

        $form->shouldReceive('getSentData')->andReturn('');

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertTrue($element->isSent());
    }

    public function testValid()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();
        $option->setRequired(array('key'));
        $option->setAllowedValues(array('key', 'key2'));

        $form->shouldReceive('getSentData')->andReturn('key');

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertTrue($element->isValid());
        $this->assertSame(array('key'), $element->getData());
        $this->assertSame(array('key'), $element->getStorableData());
    }

    public function testInValid()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();
        $option->setRequired(array('key'));
        $option->setAllowedValues(array('key', 'key2'));

        $form->shouldReceive('getSentData')->andReturn(array('key', 'key2'));

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertFalse($element->isValid());
        $this->assertNull($element->getData());
    }

    public function testAdditionalValidator()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();
        $option->setRequired(array('key'));
        $option->setAllowedValues(array('key', 'key2'));
        $validator = M::mock('\Chrome\Validator\Validator_Interface');
        $validator->shouldReceive('setData')->andReturnNull();
        $validator->shouldReceive('validate')->andReturnNull();
        $validator->shouldReceive('isValid')->andReturn(false);
        $validator->shouldReceive('getAllErrors')->andReturn(array());

        $option->setValidator($validator);

        $form->shouldReceive('getSentData')->andReturn(array('key'));

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertFalse($element->isValid());
        $this->assertNull($element->getData());
    }
}