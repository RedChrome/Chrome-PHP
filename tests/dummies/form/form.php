<?php

namespace Test\Chrome\Form;

abstract class SimpleTestForm extends \Chrome\Form\AbstractForm
{
    public function addElement(\Chrome\Form\Element\BasicElement_Interface $element)
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
        $option  = new \Chrome\Form\Option\MultipleElement();
        $element = new \Chrome\Form\Element\Checkbox($this, 'checkbox', $option);
        $this->_addElement($element);
    }
}