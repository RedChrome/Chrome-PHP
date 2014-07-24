<?php

namespace Test\Chrome\Validator\Form\Element;

use Mockery as M;

class ContainsTest extends \PHPUnit_Framework_TestCase
{
    protected function _getValidator(array $allowedValues)
    {
        return new \Chrome\Validator\Form\Element\ContainsValidator($allowedValues);
    }

    public function testContainsWithEmptyData()
    {
        $validator = $this->_getValidator(array('value1'));

        $validator->setData(null);

        $validator->validate();
        $this->assertTrue($validator->isValid());

        $this->assertNull($validator->getError());
    }

    public function testContainsWithMultipleSentValuesValid()
    {
        $validator = $this->_getValidator(array('value1', 'value2', 'value3'));

        $validator->setData(array('value1', 'value3'));

        $validator->validate();
        $this->assertTrue($validator->isValid());

        $this->assertNull($validator->getError());
    }

    public function testContainsWithMultipleSentValuesInValid()
    {
        $validator = $this->_getValidator(array('value1', 'value2', 'value3'));

        $validator->setData(array('value3', 'value4'));

        $validator->validate();
        $this->assertFalse($validator->isValid());
        $error = $validator->getError();
        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }

    public function testContainsWithSingleSentValuesValid()
    {
        $validator = $this->_getValidator(array('value1', 'value2', 'value3'));

        $validator->setData('value3');

        $validator->validate();
        $this->assertTrue($validator->isValid());

        $this->assertNull($validator->getError());
    }

    public function testContainsWithSingleSentValuesInValid()
    {
        $validator = $this->_getValidator(array('value1', 'value2', 'value3'));

        $validator->setData('value4');

        $validator->validate();
        $this->assertFalse($validator->isValid());
        $error = $validator->getError();
        $this->assertNotNull($error);
        $this->assertNotEmpty($error->getMessage());
    }
}