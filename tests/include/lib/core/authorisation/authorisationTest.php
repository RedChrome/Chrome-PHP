<?php

require_once LIB.'core/authorisation/authorisation.php';

class AuthorisationTest extends Chrome_TestCase
{
    protected $_authAdapter = null;

    protected $_auth = null;

    public function setUp()
    {
        $this->_authAdapter = new \Test\Chrome\Authorisation\Adapter\Adapter();

        $this->_auth = new \Chrome\Authorisation\Authorisation($this->_authAdapter);
    }

    public function testGetAdapter()
    {
        $this->assertTrue($this->_auth->getAuthorisationAdapter() instanceof \Chrome\Authorisation\Adapter\Adapter_Interface);

        $this->assertSame($this->_authAdapter, $this->_auth->getAuthorisationAdapter());
    }

    // TODO: test setUserId, isAllowed
}