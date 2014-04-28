<?php

namespace Test\Chrome\Authorisation\Adapter;

use Chrome\Authorisation\Resource\Resource_Interface;

class Adapter implements \Chrome\Authorisation\Adapter\Adapter_Interface
{
    public function isAllowed(Resource_Interface $obj, $userId)
    {
        // nothing to do
    }
}
