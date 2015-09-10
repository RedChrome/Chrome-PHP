<?php

namespace Test\Chrome\Form\Element;

use Mockery as M;

class PasswordElementTest extends \PHPUnit_Framework_TestCase
{
    protected $_id = 'password';

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\Form_Interface');
    }

    protected function _getElement($form, $option)
    {
        return new \Chrome\Form\Element\Password($form, $this->_id, $option);
    }

    protected function _getElementOption()
    {
        return new \Chrome\Form\Option\Element();
    }

    public function testCreate()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();

        $element = $this->_getElement($form, $option);
        $element->create();
        $this->assertTrue($element->isCreated());
    }

    public function testSent()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();
        $option->setIsRequired(true);

        $form->shouldReceive('getSentData')->andReturn('');

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertTrue($element->isSent());
    }

    public function testValid()
    {
        $form = $this->_getForm();
        $option = $this->_getElementOption();
        $option->setIsRequired(true);

        $form->shouldReceive('getSentData')->andReturn('');

        $element = $this->_getElement($form, $option);
        $element->create();

        $this->assertTrue($element->isValid());
    }
}