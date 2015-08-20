<?php

namespace Test\Chrome\Form;

class Test_Chrome_Form_Element_IsCreated extends EmptyForm
{
    public $isCreated = null;

    public $errors = array();

    protected function _isCreated()
    {

        $this->_errors = $this->errors;

        return $this->isCreated;
    }

    protected function _isSent()
    {
        return true;
    }
}