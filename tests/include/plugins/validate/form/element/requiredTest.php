<?php

namespace Test\Chrome\Validator\Form\Element;

use Mockery as M;

class RequiredTest extends \PHPUnit_Framework_TestCase
{
    protected function _getSingleOption()
    {
        return M::mock('\Chrome\Form\Option\Element_Interface');
    }

    protected function _getMultipleOption()
    {
        return M::mock('\Chrome\Form\Option\MultipleElement_Interface');
    }

    protected function _getBasicOption()
    {
        return M::mock('\Chrome\Form\Option\BasicElement_Interface');
    }

    protected function _getValidator(\Chrome\Form\Option\BasicElement_Interface $option)
    {
        return new \Chrome\Validator\Form\Element\RequiredValidator($option);
    }

    public function testSingleElementDidNotSentRequiredInput()
    {
        $option = $this->_getSingleOption();
        $option->shouldReceive('getIsRequired')->once()->withNoArgs()->andReturn(true);

        $validator = $this->_getValidator($option);
        $validator->setData(null);

        $validator->validate();

        $this->assertFalse($validator->isValid());
        $error = $validator->getError();
        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }

    public function testSingleElementDidSentRequiredInput()
    {
        $option = $this->_getSingleOption();
        $option->shouldReceive('getIsRequired')->once()->withNoArgs()->andReturn(true);

        $validator = $this->_getValidator($option);
        $validator->setData('anyData');

        $validator->validate();

        $this->assertTrue($validator->isValid());
    }

    public function testMultipleElementDidNotSentAllRequiredInput()
    {
        $option = $this->_getMultipleOption();
        $option->shouldReceive('getRequired')->withNoArgs()->once()->andReturn(array('value1', 'value2', 'value3'));

        $validator = $this->_getValidator($option);
        $validator->setData(array('value1', 'value4', 'value5'));

        $validator->validate();

        $this->assertFalse($validator->isValid());

        $error = $validator->getError();
        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }

    public function testMultipleElementDidNotSentAllRequiredInput2()
    {
        $option = $this->_getMultipleOption();
        $option->shouldReceive('getRequired')->withNoArgs()->once()->andReturn(array('value1', 'value2', 'value3'));

        $validator = $this->_getValidator($option);
        $validator->setData(array('value1', 'value4'));

        $validator->validate();

        $this->assertFalse($validator->isValid());

        $error = $validator->getError();
        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }

    public function testMultipleElementDidSentAllRequiredInput2()
    {
        $option = $this->_getMultipleOption();
        $option->shouldReceive('getRequired')->withNoArgs()->once()->andReturn(array());

        $validator = $this->_getValidator($option);
        $validator->setData('value1');

        $validator->validate();

        $this->assertTrue($validator->isValid());
    }

    public function testMultipleElementDidSentAllRequiredInput3()
    {
        $option = $this->_getMultipleOption();
        $option->shouldReceive('getRequired')->withNoArgs()->once()->andReturn(array('value1', 'value4'));

        $validator = $this->_getValidator($option);
        $validator->setData(array('value1', 'value4'));

        $validator->validate();

        $this->assertTrue($validator->isValid());
    }

    public function testBasicElementCannotSpecifyRequiredValues()
    {
        $option = $this->_getBasicOption();

        $validator = $this->_getValidator($option);
        $validator->setData(array('value1', 'value4'));

        $validator->validate();

        $this->assertTrue($validator->isValid());
    }

}