<?php

abstract class Text_Chrome_Form extends Chrome_Form_Abstract
{
    public function addElement(Chrome_Form_Element_Basic_Interface $element)
    {
        $this->_addElement($element);
    }
}

class Test_Chrome_Form_No_Elements extends Text_Chrome_Form
{
    protected function _init()
    {
    }
}

class Test_Chrome_Form_One_Element extends Text_Chrome_Form
{
    protected function _init()
    {
        $option  = new Chrome_Form_Option_Element_Multiple();
        $element = new Chrome_Form_Element_Checkbox($this, 'checkbox', $option);
        $this->_addElement($element);
    }
}