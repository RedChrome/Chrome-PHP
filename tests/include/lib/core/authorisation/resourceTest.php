<?php

namespace Test\Chrome\Authorisation\Resource;

require_once LIB.'core/authorisation/authorisation.php';

class AuthorisationResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateResource()
    {
        $assert = $this->getMock('Chrome\Authorisation\Assert\Assert_Interface');

        $resource = new \Chrome\Resource\Resource('test');
        $authResource = new \Chrome\Authorisation\Resource\Resource($resource, 'create');

        $this->assertEquals($resource, $authResource->getResource());
        $this->assertEquals('create', $authResource->getTransformation());
        $this->assertNull($authResource->getAssert());

        $resource2 = new \Chrome\Resource\Resource('test2');

        $authResource->setResource($resource2);
        $authResource->setTransformation('read');
        $authResource->setAssert($assert);

        $this->assertEquals($resource2, $authResource->getResource());
        $this->assertEquals('read', $authResource->getTransformation());
        $this->assertSame($assert, $authResource->getAssert());

    }

}