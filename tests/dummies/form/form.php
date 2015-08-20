<?php

namespace Test\Chrome\Form;

abstract class SimpleTestForm extends \Chrome_Form_Abstract
{
    public function addElement(\Chrome_Form_Element_Basic_Interface $element)
    {
        $this->_addElement($element);
    }
}

class EmptyForm extends SimpleTestForm
{
    protected function _init()
    {
    }
}

class OneElementForm extends SimpleTestForm
{
    protected function _init()
    {
        $option  = new \Chrome_Form_Option_Element_Multiple();
        $element = new \Chrome_Form_Element_Checkbox($this, 'checkbox', $option);
        $this->_addElement($element);
    }
}