<?php

namespace Test\Chrome\Validator\Form\Element;

use Mockery as M;

class ElementTest extends \PHPUnit_Framework_TestCase
{
    protected function _getValidator(\Chrome_Form_Element_Basic_Interface $formElement)
    {
        return new \Chrome\Validator\Form\Element\ElementValidator($formElement);
    }

    protected function _getFormElement()
    {
        return M::mock('\Chrome_Form_Element_Basic_Interface');
    }

    public function testValidator()
    {
        $data = 1234;

        $element = $this->_getFormElement();
        $element->shouldReceive('isValid')->once()->withNoArgs()->andReturn(false);

        $validator = $this->_getValidator($element);

        $validator->setData($data);
        $validator->validate();

        $this->assertFalse($validator->isValid());
    }
}