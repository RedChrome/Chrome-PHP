<?php

namespace Chrome\Model\Login;

class Database extends \Chrome\Model\AbstractDatabaseStatement
{
    protected function _setDatabaseOptions()
    {
        $this->_dbInterface = '\Chrome\Database\Facade\Simple';
        $this->_dbResult = '\Chrome\Database\Result\Assoc';
    }
}
