<?php

class AbstractCompositionTest extends PHPUnit_Framework_TestCase
{
    protected $_validator = null;

    public function setUp()
    {
        $this->_validator = $this->getMockForAbstractClass('\Chrome\Validator\AbstractComposition');
    }

    public function testAddingValidator()
    {
        $validator = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();

        $this->_validator->addValidator($validator);

        $this->assertEquals(1, count($this->_validator->getValidators()));
        $this->assertEquals($validator, $this->_validator->getValidator(0));

        $this->_validator->addValidator($validator2);

        $this->assertEquals(2, count($this->_validator->getValidators()));
        $this->assertEquals($validator2, $this->_validator->getValidator(1));
    }

	protected function _getValidatorMock()
	{
        return $this->getMock('\Chrome\Validator\Validator_Interface');
	}

    /**
     * @depends testAddingValidator
     */
    public function testSettingNoValidators()
    {
        $this->testAddingValidator();

        $this->_validator->setValidators(array());

        $this->assertEquals(0, count($this->_validator->getValidators()));
    }

    /**
     * @depends testAddingValidator
     */
    public function testSettingValidators()
    {
        $this->testAddingValidator();

        $validator1 = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();
        $validator3 = $this->_getValidatorMock();

        $this->_validator->setValidators(array($validator1, $validator2, $validator3, $validator2));

        $this->assertEquals(4, count($this->_validator->getValidators()));

        $this->assertEquals($validator1, $this->_validator->getValidator(0));
        $this->assertEquals($validator2, $this->_validator->getValidator(1));
        $this->assertEquals($validator3, $this->_validator->getValidator(2));
        $this->assertEquals($validator2, $this->_validator->getValidator(3));
    }

    /**
     * @depends testAddingValidator
     */
    public function testAddValidators()
    {
        $this->testAddingValidator();

        $size = count($this->_validator->getValidators());

        $validator1 = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();

        $this->_validator->addValidators(array($validator1, $validator2, $validator2));

        $this->assertEquals($validator1, $this->_validator->getValidator($size));
        $this->assertEquals($validator2, $this->_validator->getValidator($size+1));
        $this->assertEquals($validator1, $this->_validator->getValidator($size+2));
    }

    public function testAddItselfByAddValidator()
    {
        $this->setExpectedException('\Chrome\InvalidArgumentException');

        $this->_validator->addValidator($this->_validator);
    }

    public function testAddItselfByAddValidators()
    {
        $this->setExpectedException('\Chrome\InvalidArgumentException');

        $this->_validator->addValidators(array($this->_getValidatorMock(), $this->_validator, $this->_getValidatorMock()));
    }

    public function testAddItselfBySetValidators()
    {
        $this->setExpectedException('\Chrome\InvalidArgumentException');

        $this->_validator->setValidators(array($this->_getValidatorMock(), $this->_getValidatorMock(), $this->_validator));
    }

    /**
     * @depends testAddingValidator
     */
    public function testSetData()
    {
        $data = mt_rand(0, 10000);

        $validator1 = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();

        $validator1->expects($this->once())->method('setData')->with($data);
        $validator2->expects($this->once())->method('setData')->with($data);

        $this->_validator->setValidators(array($validator1, $validator2));

        $this->_validator->setData($data);
    }
}