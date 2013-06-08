<?php

require_once LIB.'core/authorisation/authorisation.php';

class AuthorisationResourceTest extends PHPUnit_Framework_TestCase
{
    public function testCreateResource() {

        $assert = new Chrome_Authorisation_Assert_Dummy();

        $resource = new Chrome_Authorisation_Resource('test', 'create');

        $this->assertEquals('test', $resource->getID());
        $this->assertEquals('create', $resource->getTransformation());
        $this->assertNull($resource->getAssert());


        $resource->setID('test2');
        $resource->setTransformation('read');
        $resource->setAssert($assert);

        $this->assertEquals('test2', $resource->getID());
        $this->assertEquals('read', $resource->getTransformation());
        $this->assertSame($assert, $resource->getAssert());

    }

}