<?php

class Chrome_Database_Adapter_Dummy extends Chrome_Database_Adapter_Abstract
{
    public $_affectedRows = 0;

    public function setDataResource($object) {
        $this->_object = $object;
    }

    public function query($query)
    {

    }

    public function getNext()
    {
        return $this->_object->getNext();
    }

    public function escape($data)
    {
        return addcslashes($data, '\'');
    }

    public function isEmpty() {
        return false;
    }

    public function getAffectedRows() {
        return $this->_affectedRows;
    }

    public function getErrorCode() {
        return 0;
    }

    public function getErrorMessage() {
        return '';
    }
}