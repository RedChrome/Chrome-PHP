<?php

namespace Test\Chrome\Form\Element;

use Mockery as M;

class ButtonsElementTest extends \PHPUnit_Framework_TestCase
{
    protected $_id = 'buttons';

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\Form_Interface');
    }

    protected function _getSubButton()
    {
        return M::mock('\Chrome\Form\Element\BasicElement_Interface');
    }

    protected function _getElement($form, $option)
    {
        return new \Chrome\Form\Element\Buttons($form, $this->_id, $option);
    }

    protected function _getOption()
    {
        return new \Chrome\Form\Option\Element\Buttons();
    }

    public function testElementCreate()
    {
        $form = $this->_getForm();
        $option = $this->_getOption();
        $button = $this->_getSubButton();
        $element = $this->_getElement($form, $option);

        $form->shouldReceive('getSentData')->andReturnNull();

        $button->shouldReceive('create')->andReturn(false);
        $button->shouldReceive('isCreated')->andReturn(false);
        $button->shouldReceive('isSent')->andReturn(false);
        $button->shouldReceive('getID')->andReturn('button');
        $button->shouldIgnoreMissing();

        $option->attach($button);


        $ret = $element->create();

        $this->assertTrue($ret);

        $this->assertFalse($element->isCreated());
        $this->assertTrue($element->isSent()); // since we did not specify whether the buttons are required
        $this->assertTrue($element->isValid());
        $this->assertSame(array('button' => null), $element->getData());
    }

    public function testElementCreate2()
    {
        $form = $this->_getForm();
        $option = $this->_getOption();
        $button = $this->_getSubButton();
        $element = $this->_getElement($form, $option);

        $form->shouldReceive('getSentData')->andReturnNull();

        $button->shouldReceive('create')->andReturn(true);
        $button->shouldReceive('isCreated')->andReturn(true);
        $button->shouldReceive('isSent')->andReturn(false);
        $button->shouldReceive('getID')->andReturn('button');
        $button->shouldIgnoreMissing();

        $option->attach($button);


        $ret = $element->create();

        $this->assertTrue($ret);

        $this->assertTrue($element->isCreated());
        $this->assertTrue($element->isSent()); // since we did not specify whether the buttons are required
        $this->assertTrue($element->isValid());
        $this->assertSame(array('button' => null), $element->getData());
    }

    public function testElementIsRequired()
    {
        $form = $this->_getForm();
        $option = $this->_getOption();
        $option->setIsRequired(true);

        $element = $this->_getElement($form, $option);

        $ret = $element->create();

        $this->assertTrue($ret);

        $this->assertTrue($element->isCreated());
        $this->assertFalse($element->isSent());
    }

    public function testElementIsReadonly()
    {
        $form = $this->_getForm();
        $option = $this->_getOption();
        $option->setIsReadonly(true);

        $element = $this->_getElement($form, $option);

        $ret = $element->create();

        $this->assertTrue($ret);

        $this->assertTrue($element->isCreated());
        $this->assertTrue($element->isSent());
    }

    public function testElementIsSent()
    {
        $form = $this->_getForm();
        $option = $this->_getOption();
        $option->setIsReadonly(false);
        $option->setIsRequired(true);

        $form->shouldReceive('getSentData')->andReturn(array('' => null));

        $button = $this->_getSubButton();
        $button->shouldReceive('create')->andReturn(true);
        $button->shouldReceive('isCreated')->andReturn(true);
        $button->shouldReceive('isSent')->andReturn(true);
        $button->shouldReceive('isValid')->andReturn(true);
        $button->shouldReceive('getID')->andReturn('button');
        $button->shouldReceive('getData')->andReturn('data');

        $button2 = $this->_getSubButton();
        $button2->shouldReceive('create')->andReturn(false);
        $button2->shouldReceive('isCreated')->andReturn(true);
        $button2->shouldReceive('isSent')->andReturn(false);
        $button2->shouldReceive('isValid')->andReturn(false);
        $button2->shouldReceive('getID')->andReturn('button2');
        $button2->shouldIgnoreMissing();

        $option->attach($button);
        $option->attach($button2);

        $element = $this->_getElement($form, $option);

        $ret = $element->create();

        $this->assertTrue($ret);

        $this->assertTrue($element->isCreated());
        $this->assertTrue($element->isSent());
        $this->assertTrue($element->isValid());
        $this->assertSame(array('button' => 'data', 'button2' => null), $element->getData());
    }
}