<?php

require_once LIB.'core/database/interface/model.php';

class Chrome_Model_Database_Statement_Dummy implements Chrome_Model_Database_Statement_Interface
{
    public $_handler = null;

    public function getStatement($key)
    {
        return ($this->_handler !== null) ? $this->_handler->getStatement($key) : null;
    }
}
