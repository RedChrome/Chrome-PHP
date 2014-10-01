<?php

namespace Test\Chrome\Validator\Form\Element;

/**
 * This class uses the mocking framework of PHPUnit, because Mockery gets removed from CodeCoverage reports
 * and this class depends on mocking
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    protected function _getValidator($callback)
    {
        return new \Chrome\Validator\Form\Element\CallbackValidator($callback);
    }

    public function testCallbackWithInvalidCallback()
    {
        $this->setExpectedException('\Chrome\Exception');

        $this->_getValidator('not a callback');
    }

    public function testCallbackViaArrayWithError()
    {
        $data = 1;

        $mock = $this->getMockBuilder('AnyClass')->setMethods(array('callback'))->getMock();
        $mock->expects($this->once())->method('callback')->will($this->returnValue('anyError'));

        $validator = $this->_getValidator(array($mock, 'callback'));

        $validator->setData($data);
        $validator->validate();

        $this->assertFalse($validator->isValid());
        $error = $validator->getError();
        $this->assertNotNull($error);
        $this->assertEquals('anyError', $error->getMessage());
    }

    public function testCallbackViaArrayValid()
    {
        $data = 1;

        $mock = $this->getMockBuilder('AnyClass')->setMethods(array('callback'))->getMock();
        $mock->expects($this->once())->method('callback')->will($this->returnValue(true));

        $validator = $this->_getValidator(array($mock, 'callback'));

        $validator->setData($data);
        $validator->validate();

        $this->assertTrue($validator->isValid());
        $this->assertNull($validator->getError());
    }

    public function testCallbackThrowsException()
    {
        $data = 1;

        $mock = $this->getMockBuilder('AnyClass')->setMethods(array('callback'))->getMock();
        $mock->expects($this->once())->method('callback')->with($data)->will($this->throwException(new \Chrome\Exception()));

        $validator = $this->_getValidator(array($mock, 'callback'));

        $validator->setData($data);
        $validator->validate();

        $this->assertFalse($validator->isValid());
        $error = $validator->getError();
        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }

    public function testCallbackViaLambdaValid()
    {
        $data = 1;

        $mock = $this->getMockBuilder('AnyClass')->setMethods(array('callback'))->getMock();
        $mock->expects($this->once())->method('callback')->with($data)->will($this->returnValue(true));

        $validator = $this->_getValidator(function ($data) use ($mock) {
            return $mock->callback($data);
        });

        $validator->setData($data);
        $validator->validate();

        $this->assertTrue($validator->isValid());
        $this->assertNull($validator->getError());
    }
}