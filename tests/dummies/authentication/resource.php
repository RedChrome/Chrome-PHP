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

    public function getID()
    {
        return $this->_id;
    }


}
