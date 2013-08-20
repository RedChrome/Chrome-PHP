<?php

class Test_Chrome_Form_Element_isValid extends Chrome_Form_Element_Abstract
{
    public $isValid = null;

    public $errors = array();

    public function isValid() {

        $this->_errors = $this->errors;

        return $this->isValid;
    }

    protected function _isCreated()
    {
        return true;
    }

    protected function _isSent() {
        return true;
    }
}