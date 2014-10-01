<?php

namespace Test\Chrome\Validator\Composition;

use Mockery as M;

class AndTest extends \PHPUnit_Framework_TestCase
{
    protected $_validator = null;

    public function setUp()
    {
        $this->_validator = new \Chrome\Validator\Composition\AndComposition();
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

        $data = mt_rand(0, 1000000);

        $validator1->shouldReceive('setData')->with($data)->once();
        $validator2->shouldReceive('setData')->with($data)->once();
        $validator3->shouldReceive('setData')->with($data)->once();

        $validator1->shouldReceive('validate')->withAnyArgs()->once();
        $validator2->shouldReceive('validate')->withAnyArgs()->once();
        $validator3->shouldReceive('validate')->withAnyArgs()->once();

        $validator1->shouldReceive('isValid')->withAnyArgs()->once()->andReturn(true);
        $validator2->shouldReceive('isValid')->withAnyArgs()->once()->andReturn(true);
        $validator3->shouldReceive('isValid')->withAnyArgs()->once()->andReturn(true);

        $validator2->shouldReceive('getAllErrors')->never()->withAnyArgs();

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

        $error = array(M::mock('\Chrome\Localization\Message_Interface')->shouldIgnoreMissing(null));

        $validator1->shouldReceive('validate')->once();
        $validator2->shouldReceive('validate')->once();
        $validator3->shouldReceive('validate')->never();

        $validator1->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(true);
        $validator2->shouldReceive('isValid')->once()->withAnyArgs()->andReturn(false);
        $validator3->shouldReceive('isValid')->never()->withAnyArgs()->andReturn(true);

        $validator2->shouldReceive('getAllErrors')->once()->withAnyArgs()->andReturn($error);

        $this->_validator->setData(null);
        $this->_validator->validate();

        $this->assertFalse($this->_validator->isValid());

        $this->assertEquals($error, $this->_validator->getAllErrors());
    }
}