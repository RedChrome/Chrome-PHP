<?php

namespace Test\Chrome\Validator\Form\Element;

use Mockery as M;

class ReadonlyTest extends \PHPUnit_Framework_TestCase
{
    public function testReadonlyIsValid()
    {
        $data = 123;

        $option = M::mock('\Chrome_Form_Option_Element_Interface');
        $option->shouldReceive('getIsReadonly')->withNoArgs()->andReturn(true)->once();
        $validator = new \Chrome\Validator\Form\Element\ReadonlyValidator($option);

        $validator->setData($data);
        $validator->validate();

        $this->assertTrue($validator->isValid());
    }

    public function testReadonlyIsInValid()
    {
        $data = 123;

        $option = M::mock('\Chrome_Form_Option_Element_Interface');
        $option->shouldReceive('getIsReadonly')->withNoArgs()->andReturn(false)->once();
        $validator = new \Chrome\Validator\Form\Element\ReadonlyValidator($option);

        $validator->setData($data);
        $validator->validate();

        $this->assertFalse($validator->isValid());
        $this->assertNull($validator->getError());
    }


}