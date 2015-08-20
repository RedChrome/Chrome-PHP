<?php

namespace Test\Chrome\Form\Element;

class IsCreated extends \Chrome_Form_Element_Abstract
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