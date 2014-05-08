<?php

class AndCompositionTest extends PHPUnit_Framework_TestCase
{
    protected $_validator = null;

    public function setUp()
    {
        $this->_validator = new \Chrome\Validator\Composition\AndComposition();
    }

    protected function _getValidatorMock()
    {
        return $this->getMock('\Chrome\Validator\Validator_Interface');
    }

    public function testValidateSuccess()
    {
        $validator1 = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();
        $validator3 = $this->_getValidatorMock();

        $this->_validator->addValidators(array($validator1, $validator2, $validator3));

        // not called validate before...
        $this->assertFalse($this->_validator->isValid());

        $data = mt_rand(0, 1000000);

        $validator1->expects($this->once())->method('setData')->with($data);
        $validator2->expects($this->once())->method('setData')->with($data);
        $validator3->expects($this->once())->method('setData')->with($data);

        $validator1->expects($this->once())->method('validate');
        $validator2->expects($this->once())->method('validate');
        $validator3->expects($this->once())->method('validate');

        $validator1->expects($this->once())->method('isValid')->will($this->returnValue(true));
        $validator2->expects($this->once())->method('isValid')->will($this->returnValue(true));
        $validator3->expects($this->once())->method('isValid')->will($this->returnValue(true));

        $validator2->expects($this->never())->method('getAllErrors')->will($this->returnValue(array($this->getMock('\Chrome\Localization\Message_Interface'))));

        $this->_validator->setData($data);
        $this->_validator->validate();

        $this->assertTrue($this->_validator->isValid());

        $this->assertEquals(array(), $this->_validator->getAllErrors());
    }

    /**
     * @depends testValidateSuccess
     */
    public function testValidateOneValidatorReturnsFalse()
    {
        $validator1 = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();
        $validator3 = $this->_getValidatorMock();

        $this->_validator->addValidators(array($validator1, $validator2, $validator3));

        $error = array($this->getMock('\Chrome\Localization\Message_Interface'));

        $validator1->expects($this->once())->method('validate');
        $validator2->expects($this->once())->method('validate');
        $validator3->expects($this->never())->method('validate');

        $validator1->expects($this->once())->method('isValid')->will($this->returnValue(true));
        $validator2->expects($this->once())->method('isValid')->will($this->returnValue(false));
        $validator3->expects($this->never())->method('isValid')->will($this->returnValue(true));

        $validator2->expects($this->once())->method('getAllErrors')->will($this->returnValue($error));

        $this->_validator->setData(null);
        $this->_validator->validate();

        $this->assertFalse($this->_validator->isValid());

        $this->assertEquals($error, $this->_validator->getAllErrors());
    }
}