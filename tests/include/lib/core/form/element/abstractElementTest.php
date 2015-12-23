<?php

namespace Test\Chrome\Form\Element;

use Mockery as M;
use Chrome\Form\Element\AbstractElement;

class AbstractElementMock extends AbstractElement
{
}

class AbstractElementTest extends \Test\Chrome\TestCase
{

    protected function _getElementOption()
    {
        return M::mock('\Chrome\Form\Option\Element_Interface');
    }

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\AbstractForm');
    }

    protected function _getValidator()
    {
        return M::mock('\Chrome\Validator\Validator_Interface');
    }

    public function testIsSentIfNotRequired()
    {
        $id = $this->getFaker()->text;
        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $option->shouldReceive('getIsRequired')->once()->andReturn(false);

        $element = new AbstractElementMock($form, $id, $option);

        $this->assertTrue($element->isSent());
    }

    public function testIsSentIfNotRequiReadonly()
    {
        $id = $this->getFaker()->text;
        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $option->shouldReceive('getIsRequired')->once()->andReturn(true);
        $option->shouldReceive('getIsReadonly')->once()->andReturn(true);

        $element = new AbstractElementMock($form, $id, $option);

        $this->assertTrue($element->isSent());
    }

    public function testRenew()
    {
        $id = $this->getFaker()->text;
        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $element = new AbstractElementMock($form, $id, $option);

        // does nothing
        $this->assertNull($element->renew());
    }

    public function testDestroy()
    {
        $id = $this->getFaker()->text;
        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $element = new AbstractElementMock($form, $id, $option);

        // does nothing
        $this->assertNull($element->destroy());
    }

    public function testGetDataIfReadonly()
    {
        $id = $this->getFaker()->text;
        $option = $this->_getElementOption();
        $form = $this->_getForm();
        $validator = $this->_getValidator();

        $validator->shouldReceive('setData')->once()->andReturnNull();

        $form->shouldReceive('getSentData')->once()->andReturn(array($this->getFaker()->text));

        $option->shouldReceive('getAllowedValue')->once()->andReturnNull();
        $option->shouldReceive('getValidator')->once()->andReturn($validator);
        $option->shouldReceive('getIsReadonly')->andReturn(true);

        $element = new AbstractElementMock($form, $id, $option);

        $this->assertNull($element->getData());
    }

    public function testGetDataIfNotReadonly()
    {
        $id = $this->getFaker()->text;
        $option = $this->_getElementOption();
        $form = $this->_getForm();
        $validator = $this->_getValidator();

        $data = array($this->getFaker()->text);

        $validator->shouldReceive('setData')->once()->andReturnNull();
        $validator->shouldReceive('validate')->once()->andReturnNull();
        $validator->shouldReceive('isValid')->once()->andReturn(true);

        $form->shouldReceive('getSentData')->andReturn($data);
        $option->shouldReceive('getIsRequired')->once()->andReturn(true);
        $option->shouldReceive('getAllowedValue')->once()->andReturnNull();
        $option->shouldReceive('getValidator')->once()->andReturn($validator);
        $option->shouldReceive('getIsReadonly')->andReturn(false);
        $option->shouldReceive('getConversion')->andReturnNull();

        $element = new AbstractElementMock($form, $id, $option);

        $this->assertEquals($data, $element->getData());
    }

    public function testGetValidatorWithAllowdValues()
    {
        $id = $this->getFaker()->text;
        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $faker = $this->getFaker();
        $data1 = $faker->text;
        $data2 = $faker->text;
        $data3 = $faker->text;
        $data4 = $faker->text;

        $form->shouldReceive('getSentData')->once()->andReturn($data1);
        $option->shouldReceive('getAllowedValue')->once()->andReturn(array($data1, $data2, $data3, $data4));
        $option->shouldReceive('getValidator')->once()->andReturnNull();
        $option->shouldReceive('getIsRequired')->andReturn(true);
        $option->shouldReceive('getIsReadonly')->andReturn(false);

        $element = new AbstractElementMock($form, $id, $option);
        $this->assertTrue($element->isValid());
    }
}