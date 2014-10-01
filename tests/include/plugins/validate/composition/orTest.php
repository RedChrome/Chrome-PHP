<?php

namespace Test\Chrome\Validator\Composition;

use Mockery as M;

class OrTest extends \PHPUnit_Framework_TestCase
{
    protected $_validator = null;

    public function setUp()
    {
        $this->_validator = new \Chrome\Validator\Composition\OrComposition();
    }

    protected function _getValidatorMock()
    {
        $mock = M::mock('\Chrome\Validator\Validator_Interface');
        $mock->shouldIgnoreMissing(null);
        return $mock;
    }

    public function testValidateSuccess()
    {
        $validator1 = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();
        $validator3 = $this->_getValidatorMock();

        $this->_validator->addValidators(array($validator1, $validator2, $validator3));

        // not called validate before...
        $this->assertFalse($this->_validator->isValid());

        $validator1->shouldReceive('validate')->once();
        $validator2->shouldReceive('validate')->once();
        $validator3->shouldReceive('validate')->once();

        $validator1->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(false);
        $validator2->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(false);
        $validator3->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(true);

        $validator2->shouldReceive('getAllErrors')->once()->withAnyArgs()->andReturn(array(M::mock('\Chrome\Localization\Message_Interface')->shouldIgnoreMissing(null)));
        $validator1->shouldReceive('getAllErrors')->once()->withAnyArgs()->andReturn(array(M::mock('\Chrome\Localization\Message_Interface')->shouldIgnoreMissing(null)));

        $this->_validator->setData(null);
        $this->_validator->validate();

        $this->assertTrue($this->_validator->isValid());

        // tests that all previous errors are forgotten
        $this->assertEquals(array(), $this->_validator->getAllErrors());
    }

    /**
     * @depends testValidateSuccess
     */
    public function testValidateSubsequenValidatorsAreSkippedIfReturnedTrue()
    {
        $validator1 = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();
        $validator3 = $this->_getValidatorMock();

        $this->_validator->addValidators(array($validator1, $validator2, $validator3));

        $error = array(M::mock('\Chrome\Localization\Message_Interface')->shouldIgnoreMissing(null));

        $validator1->shouldReceive('validate')->once();
        $validator2->shouldReceive('validate')->never();
        $validator3->shouldReceive('validate')->never();

        $validator1->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(true);
        $validator2->shouldReceive('isValid')->never()->withAnyArgs()->andReturn(false);
        $validator3->shouldReceive('isValid')->never()->withAnyArgs()->andReturn(false);

        $validator1->shouldReceive('getAllErrors')->never()->withAnyArgs()->andReturn($error);
        $validator2->shouldReceive('getAllErrors')->never()->withAnyArgs()->andReturn($error);
        $validator3->shouldReceive('getAllErrors')->never()->withAnyArgs()->andReturn($error);

        $this->_validator->setData(null);
        $this->_validator->validate();

        $this->assertTrue($this->_validator->isValid());

        $this->assertEquals(array(), $this->_validator->getAllErrors());
    }

    /**
     * @depends testValidateSuccess
     */
    public function testValidateAllValidatorsAreInvalid()
    {
        $validator1 = $this->_getValidatorMock();
        $validator2 = $this->_getValidatorMock();
        $validator3 = $this->_getValidatorMock();

        $this->_validator->addValidators(array($validator1, $validator2, $validator3));

        $error = array(M::mock('\Chrome\Localization\Message_Interface')->shouldIgnoreMissing(null));

        $validator1->shouldReceive('validate')->once();
        $validator2->shouldReceive('validate')->once();
        $validator3->shouldReceive('validate')->once();

        $validator1->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(false);
        $validator2->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(false);
        $validator3->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(false);

        $validator1->shouldReceive('getAllErrors')->once()->withNoArgs()->andReturn(array());
        $validator2->shouldReceive('getAllErrors')->once()->withNoArgs()->andReturn(array());
        $validator3->shouldReceive('getAllErrors')->once()->withNoArgs()->andReturn($error);

        $this->_validator->setData(null);
        $this->_validator->validate();

        $this->assertFalse($this->_validator->isValid());

        $this->assertEquals($error, $this->_validator->getAllErrors());
    }
}