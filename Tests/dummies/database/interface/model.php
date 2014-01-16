<?php

class Test_Chrome_Model_Database_Statement extends Chrome_Model_Database_Statement
{
    protected function _createCache($database, $namespace)
    {
        $cacheOption = new \Chrome\Cache\Option\File\Json();
        $cacheOption->setCacheFile(ROOT . '/Tests/resources/database/' . strtolower($database) . '/' . strtolower($namespace) . '.json');
        return new \Chrome\Cache\File\Json($cacheOption);
    }
}