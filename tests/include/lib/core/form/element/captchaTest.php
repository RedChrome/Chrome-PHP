<?php

namespace Test\Chrome\Form\Element;

use Mockery as M;

class CaptchaElementTest extends \PHPUnit_Framework_TestCase
{
    protected $_id = 'captcha';

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\Form_Interface');
    }

    protected function _getElement($form, $option)
    {
        return new \Chrome\Form\Element\Captcha($form, $this->_id, $option);
    }

    protected function _getOption($form)
    {
        return new \Chrome\Form\Option\Element\Captcha($form);
    }

    protected function _getCaptcha()
    {
        return M::mock('\Chrome\Captcha\Captcha_Interface');
    }

    public function testNotCreated()
    {
        $form = $this->_getForm();
        $option = $this->_getOption($form);
        $captcha = $this->_getCaptcha();

        $option->setCaptcha($captcha);
        $option->setIsRequired(true);

        $captcha->shouldNotReceive('create');

        $element = $this->_getElement($form, $option);
        $this->assertFalse($element->isCreated());
    }

    public function testCreate()
    {
        $form = $this->_getForm();
        $option = $this->_getOption($form);
        $captcha = $this->_getCaptcha();

        $option->setCaptcha($captcha);
        $option->setIsRequired(true);

        $captcha->shouldReceive(array('create' => 1))->andReturnNull();
        $form->shouldReceive('getSentData')->andReturnNull();


        $element = $this->_getElement($form, $option);
        $element->create();
        $this->assertTrue($element->isCreated());
        $this->assertFalse($element->isSent());
    }

    public function testIsSent()
    {
        $form = $this->_getForm();
        $option = $this->_getOption($form);
        $captcha = $this->_getCaptcha();

        $option->setCaptcha($captcha);
        $option->setIsRequired(true);
        $option->setRecreateIfInvalid(true);

        $captcha->shouldReceive(array('create' => 2))->andReturnNull();
        $captcha->shouldReceive('isValid')->andReturn(false);
        $form->shouldReceive('getSentData')->andReturn(array('captcha' => ''));

        $element = $this->_getElement($form, $option);
        $element->create();
        $this->assertTrue($element->isCreated());
        $this->assertTrue($element->isSent());
        $this->assertFalse($element->isValid());
    }

    public function testIsValid()
    {
        $form = $this->_getForm();
        $option = $this->_getOption($form);
        $captcha = $this->_getCaptcha();

        $option->setCaptcha($captcha);
        $option->setIsRequired(true);

        $captcha->shouldReceive(array('create' => 1))->andReturnNull();
        $captcha->shouldReceive('isValid')->andReturn(true);
        $form->shouldReceive('getSentData')->andReturn(array('captcha' => ''));

        $element = $this->_getElement($form, $option);
        $element->create();
        $this->assertTrue($element->isCreated());
        $this->assertTrue($element->isSent());
        $this->assertTrue($element->isValid());
    }
}