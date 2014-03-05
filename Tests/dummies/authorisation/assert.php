<?php

//require_once LIB.'core/authorisation/assert'

class Chrome_Authorisation_Assert_Dummy extends \Chrome\Authorisation\Assert\Assert_Abstract
{
    public function assert(\Chrome\Authorisation\Resource\Resource_Interface $resource) {
        return true;
    }
}