<?php

namespace Test\Chrome\Authentication\Resource;

use \Chrome\Authentication\CreateResource_Interface;
use \Chrome\Authentication\Resource_Interface;

require_once LIB.'core/authentication/authentication.php';

class Dummy implements Resource_Interface
{
    public $_id = 1;

    public function getID()
    {
        return $this->_id;
    }

}

class Create_Dummy implements CreateResource_Interface
{
    public $_id = 0;

    private $_success = false;

    public function getID()
    {
        return $this->_id;
    }

    public function success()
    {
        $this->_success = true;
    }

    public function isSuccessful()
    {
        return $this->_success;
    }
}
