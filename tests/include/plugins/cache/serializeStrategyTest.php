<?php

namespace Test\Chrome\Cache;

class SerializeStrategyTest extends JsonStrategyTest
{
    protected function _getOption()
    {
        return new \Chrome\Cache\Option\File\Serialization();
    }

    protected function _getCache($option)
    {
        return new \Chrome\Cache\File\Serialization($option);
    }
}