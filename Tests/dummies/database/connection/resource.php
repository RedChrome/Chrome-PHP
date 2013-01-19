<?php

require_once LIB.'core/authentication/authentication.php';

class Chrome_Authentication_Resource_Dummy implements Chrome_Authentication_Resource_Interface
{
    public $_id = 1;

    public function getID()
    {
        return $this->_id;
    }

}
