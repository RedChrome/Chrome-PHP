<?php

class Test_Chrome_Model_Database_Statement extends Chrome_Model_Database_Statement
{
    protected function _setUpCache()
    {
        $this->_cacheOption = new Chrome_Cache_Option_Json();
        $this->_cacheOption->setCacheFile(ROOT . '/Tests/resources/database/' . strtolower($this->_database) . '/' . strtolower($this->_namespace) . '.json');
        $this->_cacheInterface = 'Json';
    }
}