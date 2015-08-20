<?php

namespace Test\Chrome\Model\Database;

class Statement extends \Chrome\Model\Database\JsonStatement
{
    protected function _createCache($database, $namespace)
    {
        $cacheOption = new \Chrome\Cache\Option\File\Json();
        $cacheOption->setCacheFile(ROOT . '/tests/resources/database/' . strtolower($database) . '/' . strtolower($namespace) . '.json');
        return new \Chrome\Cache\File\Json($cacheOption);
    }
}