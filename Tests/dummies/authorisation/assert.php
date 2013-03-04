<?php

//require_once LIB.'core/authorisation/assert'

class Chrome_Authorisation_Assert_Dummy extends Chrome_Authorisation_Assert_Abstract
{
    public function assert(Chrome_Authorisation_Resource_Interface $resource) {
        return true;
    }
}