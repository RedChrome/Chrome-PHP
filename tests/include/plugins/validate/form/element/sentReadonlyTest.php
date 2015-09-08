<?php

namespace Test\Chrome\Validator\Form\Element;

use Mockery as M;

class SentReadonlyTest extends \PHPUnit_Framework_TestCase
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
        return new \Chrome\Validator\Form\Element\SentReadonlyValidator($option);
    }

    public function testSingleElementDidNotSentReadonlyInput()
    {
        $option = $this->_getSingleOption();
        $option->shouldReceive('getIsReadonly')->once()->withNoArgs()->andReturn(true);

        $validator = $this->_getValidator($option);

        $validator->setData(null);
        $validator->validate();

        $this->assertTrue($validator->isValid());

        $this->assertNull($validator->getError());
    }

    public function testSingleElementDidSentReadonlyInput()
    {
        $option = $this->_getSingleOption();
        $option->shouldReceive('getIsReadonly')->once()->withNoArgs()->andReturn(true);

        $validator = $this->_getValidator($option);

        $validator->setData('anyData');
        $validator->validate();

        $this->assertFalse($validator->isValid());

        $error = $validator->getError();

        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());

    }

    public function testMultipleElementDidNotSentReadonlyInput()
    {
        $option = $this->_getMultipleOption();
        $option->shouldReceive('getReadonly')->twice()->withNoArgs()->andReturn(array('value3', 'value4'));

        $validator = $this->_getValidator($option);

        $validator->setData(array('value1', 'value2', 'value5'));
        $validator->validate();

        $this->assertTrue($validator->isValid());

        $this->assertNull($validator->getError());

        $validator = $this->_getValidator($option);

        $validator->setData('value2');
        $validator->validate();

        $this->assertTrue($validator->isValid());
    }

    public function testMultipleElementDidSentReadonlyInput()
    {
        $option = $this->_getMultipleOption();
        $option->shouldReceive('getReadonly')->twice()->withNoArgs()->andReturn(array('value3', 'value4'));

        $validator = $this->_getValidator($option);

        $validator->setData(array('value1', 'value2', 'value3'));
        $validator->validate();

        $this->assertFalse($validator->isValid());

        $error = $validator->getError();

        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());

        $validator = $this->_getValidator($option);

        $validator->setData('value4');
        $validator->validate();

        $this->assertFalse($validator->isValid());

        $error = $validator->getError();

        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }

    public function testBasicElementCannotSpecifyReadOnlyInput()
    {
        $option = $this->_getBasicOption();

        $validator = $this->_getValidator($option);

        $validator->setData('anyData');
        $validator->validate();

        $this->assertTrue($validator->isValid());

        $this->assertNull($validator->getError());
    }
}