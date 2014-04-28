<?php

namespace Test\Chrome\Model;

class NullModel extends \Chrome_Model_Abstract
{
    public function __call($methodName, array $params) {
        return null;
    }
}