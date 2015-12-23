<?php

namespace Test\Chrome\Form\Element;

use Mockery as M;
use \Chrome\Form\Element\AbstractBasicElement;

class AbstractBasicElementMock extends AbstractBasicElement
{
    protected function _getData()
    {}

    public function create()
    {}

    public function destroy()
    {}

    public function renew()
    {}

    public function convert($data)
    {
        return $this->_convert($data);
    }
}

class AbstractElementBasicTest extends \Test\Chrome\TestCase
{
    protected function _getElementOption()
    {
        return M::mock('\Chrome\Form\Option\Element_Interface');
    }

    protected function _getForm()
    {
        return M::mock('\Chrome\Form\AbstractForm');
    }

    public function test_IsCreated()
    {
        $faker = $this->getFaker();
        $id = $faker->text;

        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $element = new AbstractBasicElementMock($form, $id, $option);

        $this->setExpectedException('Chrome\Exception');
        $element->isCreated();
    }

    public function test_getValidator()
    {
        $faker = $this->getFaker();
        $id = $faker->text;

        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $element = new AbstractBasicElementMock($form, $id, $option);

        $this->setExpectedException('Chrome\Exception');
        $element->isValid();
    }

    public function testGettters()
    {
        $faker = $this->getFaker();
        $id = $faker->text;

        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $element = new AbstractBasicElementMock($form, $id, $option);

        $this->assertEquals($option, $element->getOption());
        $this->assertEquals($form, $element->getForm());
        $this->assertEquals($id, $element->getID());
        // $this->assertEquals($element, $form->getElements($id));
    }

    public function testConvertData()
    {
        $faker = $this->getFaker();
        $id = $faker->text;

        $option = $this->_getElementOption();
        $form = $this->_getForm();

        $convertedData = $faker->text;
        $data = $faker->text;

        $converter = M::mock('\Chrome\Converter\Converter_Interface');
        $converter->shouldReceive('convert')->andReturn($convertedData);
        $appContext = M::mock('\Chrome\Context\Application_Interface');
        $appContext->shouldReceive('getConverter')->andReturn($converter);

        $option->shouldReceive('getConversion')->andReturn(M::mock('\Chrome\Converter\List_Interface'));
        $form->shouldReceive('getApplicationContext')->andReturn($appContext);

        $element = new AbstractBasicElementMock($form, $id, $option);

        $this->assertEquals($convertedData, $element->convert($data));
    }
}