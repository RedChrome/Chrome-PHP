<?php

class Test_Chrome_Form_Element_isSent extends Chrome_Form_Element_Abstract
{
    public $isSent = null;

    public $errors = array();

    protected function _isSent() {

        $this->_errors = $this->errors;

        return $this->isSent;
    }

    protected function _isCreated()
    {
        return true;
    }
}