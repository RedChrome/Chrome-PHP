<?php
namespace Test\Chrome\Database\Adapter;

class Dummy extends \Chrome\Database\Adapter\AbstractAdapter
{
    public $_affectedRows = 0;

    public function setDataResource($object)
    {
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

    public function isEmpty()
    {
        return false;
    }

    public function getAffectedRows()
    {
        return $this->_affectedRows;
    }

    public function getErrorCode()
    {
        return 0;
    }

    public function getErrorMessage()
    {
        return '';
    }

    public function getLastInsertId()
    {
        return 0;
    }
}
