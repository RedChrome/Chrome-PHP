<?php

namespace Test\Chrome\Model\Database;

require_once LIB.'core/database/facade/model.php';

class DummyStatement implements \Chrome\Model\Database\Statement_Interface
{
    public $_handler = null;

    protected $_namespace;
    protected $_database;

    public function getStatement($key)
    {
        return ($this->_handler !== null) ? $this->_handler->getStatement($key) : null;
    }

    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }

    public function setDatabaseName($databaseName) {
        $this->_database = $databaseName;
    }
}
