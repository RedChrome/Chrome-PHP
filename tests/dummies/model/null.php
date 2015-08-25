<?php

namespace Test\Chrome\Model;

class NullModel extends \Chrome\Model\AbstractModel
{
    public function __call($methodName, array $params)
    {
        return null;
    }
}