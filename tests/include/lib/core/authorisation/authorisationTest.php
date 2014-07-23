<?php

namespace Test\Chrome\Authorisation;

use Mockery as M;

require_once LIB.'core/authorisation/authorisation.php';

class AuthorisationTest extends \Test\Chrome\TestCase
{
    protected $_authAdapter = null;

    protected $_auth = null;

    public function setUp()
    {
        $this->_authAdapter = M::mock('\Chrome\Authorisation\Adapter\Adapter_Interface');

        $this->_auth = new \Chrome\Authorisation\Authorisation($this->_authAdapter);
    }

    public function testGetAdapter()
    {
        $this->assertTrue($this->_auth->getAuthorisationAdapter() instanceof \Chrome\Authorisation\Adapter\Adapter_Interface);

        $this->assertSame($this->_authAdapter, $this->_auth->getAuthorisationAdapter());
    }

    public function testSetUserId()
    {
        $userId = 142;

        $resource = M::mock('\Chrome\Authorisation\Resource\Resource_Interface');
        $resource->shouldIgnoreMissing(null);

        $this->_authAdapter->shouldIgnoreMissing(false)->shouldReceive('isAllowed')->with($resource, $userId)->once()->andReturn(true);

        $this->assertFalse($this->_auth->isAllowed($resource));
        $this->_auth->setUserId($userId);
        $this->assertTrue($this->_auth->isAllowed($resource));
    }

    public function testIsAllowedWithAssertions()
    {
        $assert = M::mock('\Chrome\Authorisation\Assert\Assert_Interface');
        $assert->shouldReceive('assert')->times(3)->withAnyArgs()->andReturn(true, false, true);
        $assert->shouldReceive('getOption')->times(3)->with('return')->andReturn(true, true, false);

        $resource = M::mock('\Chrome\Authorisation\Resource\Resource_Interface');
        $resource->shouldReceive('getAssert')->times(3)->withNoArgs()->andReturn($assert);
        $resource->shouldIgnoreMissing(null);

        $this->_authAdapter->shouldReceive('isAllowed')->with($resource, M::any())->once()->andReturn(false);

        $this->assertTrue($this->_auth->isAllowed($resource));
        $this->assertFalse($this->_auth->isAllowed($resource));
        $this->assertFalse($this->_auth->isAllowed($resource));

    }
}