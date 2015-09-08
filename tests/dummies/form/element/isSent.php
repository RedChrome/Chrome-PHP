<?php

namespace Test\Chrome\Form\Element;

class IsSent extends \Chrome\Form\Element\AbstractElement
{
    public $isSent = null;

    public $errors = array();

    protected function _isSent()
    {

        $this->_errors = $this->errors;

        return $this->isSent;
    }

    protected function _isCreated()
    {
        return true;
    }
}