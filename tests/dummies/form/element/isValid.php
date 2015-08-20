<?php

namespace Test\Chrome\Form\Element;

class IsValid extends \Chrome_Form_Element_Abstract
{
    public $isValid = null;

    public $errors = array();

    public function isValid($elementName = null)
    {
        $this->_errors = $this->errors;

        return $this->isValid;
    }

    protected function _isCreated()
    {
        return true;
    }

    protected function _isSent()
    {
        return true;
    }
}