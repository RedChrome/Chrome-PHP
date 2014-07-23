<?php

namespace Test\Chrome\Authentication;

use Mockery as M;

require_once 'tests/dummies/authentication/resource.php';
require_once 'tests/dummies/authentication/chain.php';
require_once 'tests/dummies/cookie.php';

class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    protected $_auth = null;

    public function setUp()
    {
        $this->_auth = new \Chrome\Authentication\Authentication();
    }

    protected function _getChainMock()
    {
        return M::mock('\Chrome\Authentication\Chain\Chain_Interface')->shouldIgnoreMissing(null);
    }

    protected function _getCreateResourceMock()
    {
        return M::mock('\Chrome\Authentication\CreateResource_Interface');
    }

    protected function _getResourceMock()
    {
        return M::mock('\Chrome\Authentication\Resource_Interface');
    }

    protected function _getExceptionHandlerMock()
    {
        return M::mock('\Chrome\Exception\Handler_Interface')->shouldIgnoreMissing(null);
    }

    protected function _getAuthenticationException()
    {
        return new \Chrome\Exception\Authentication();
    }

    protected function _getContainerMock()
    {
        return M::mock('\Chrome\Authentication\Container_Interface');
    }

    public function testGetChain()
    {
        // every authentication object has to have at least one chain
        $this->assertInstanceOf('\Chrome\Authentication\Chain\Chain_Interface', $this->_auth->getChain());

        $chain = $this->_getChainMock();
        $this->_auth->setChain($chain);
        $this->assertSame($chain, $this->_auth->getChain());
    }

    public function testAddChain()
    {
        $firstChain  = $this->_getChainMock();
        $secondChain = $this->_getChainMock();

        $this->_auth->setChain($firstChain);
        $this->assertSame($firstChain, $this->_auth->getChain());

        $this->_auth->addChain($secondChain);

        $this->_auth->setChain($secondChain);
        $this->assertSame($secondChain, $this->_auth->getChain());
    }

    public function testAuthenticateWithNoInfo()
    {
        $this->_auth->authenticate();

        $this->assertFalse($this->_auth->isUser());
        $this->assertTrue($this->_auth->isAuthenticated());
    }

    public function testCreateAuthentication()
    {
        $chain = $this->_auth->getChain();
        $resource = $this->_getCreateResourceMock();

        $this->_auth->setExceptionHandler($this->_getExceptionHandlerMock());
        $exceptionHandler = $this->_getExceptionHandlerMock();
        $this->_auth->setExceptionHandler($exceptionHandler);
        $this->assertSame($exceptionHandler, $this->_auth->getExceptionHandler());

        // throws a exception while creating authentication
        $chain = $this->_getChainMock();
        $chain->shouldReceive('createAuthentication')->zeroOrMoreTimes()->andThrow($this->_getAuthenticationException());
        // this will throw an exception, but it is caught in the exception handler
        $this->_auth->setChain($chain)->createAuthentication($resource);

        // this will not throw an exception
        $this->_auth->setChain($this->_getChainMock())->createAuthentication($resource);

        $this->_auth->setChain($chain);
    }

    public function testExceptionHandlerWhileAuthenticaing()
    {
        $resource = $this->_getResourceMock();
        $this->_auth->setExceptionHandler($this->_getExceptionHandlerMock());

        $chain = $this->_getChainMock();
        $chain->shouldReceive('authenticate')->zeroOrMoreTimes()->with($resource)->andThrow($this->_getAuthenticationException());

        $this->_auth->setChain($chain)->authenticate($resource);
    }

    /**
     * @depends testExceptionHandlerWhileAuthenticaing
     */
    public function testExceptionHandlerWhileAuthenticaingWithError()
    {
        $resource = $this->_getResourceMock();

        // do not set an exception handler, this will give us the exception
        $this->setExpectedException('\Chrome\Exception\Authentication');

        $chain = $this->_getChainMock();
        $chain->shouldReceive('authenticate')->zeroOrMoreTimes()->with($resource)->andThrow($this->_getAuthenticationException());

        $this->_auth->setChain($chain)->authenticate($resource);
    }

    public function testAuthenticateCouldNotAuthenticate()
    {
        $chain = $this->_getChainMock();
        $this->_auth->addChain($chain);

        $this->setExpectedException('\Chrome\Exception\Authentication');

        $this->_auth->authenticate();
    }

    public function testAuthenticateHandlesWrongIDs()
    {
        $chain = $this->_getChainMock();
        $chain->shouldReceive('authenticate')->zeroOrMoreTimes()->andReturn('1f921');
        $this->_auth->addChain($chain);

        $this->setExpectedException('\Chrome\Exception\Authentication');

        $this->_auth->authenticate();
    }

    public function testAuthenticateSuccessfull()
    {
        $id = mt_rand(1, 100);

        $container = $this->_getContainerMock();
        $container->shouldReceive('getID')->zeroOrMoreTimes()->andReturn($id);
        $container->shouldReceive('getStatus')->zeroOrMoreTimes()->andReturn(\Chrome\Authentication\Container_Interface::STATUS_USER);
        $chain = $this->_getChainMock();
        $chain->shouldReceive('authenticate')->zeroOrMoreTimes()->andReturn($container);

        $this->_auth->addChain($chain);

        $this->_auth->authenticate();

        $this->assertEquals($id, $this->_auth->getAuthenticationID());
        $this->assertTrue($this->_auth->isAuthenticated());
        $this->assertInstanceOf('\Chrome\Authentication\Container_Interface', $this->_auth->getAuthenticationDataContainer());
    }

    public function testDeauthenticate()
    {
        $this->testAuthenticateSuccessfull();

        $this->_auth->deAuthenticate();
        $this->assertFalse($this->_auth->isAuthenticated());
        $this->assertEquals(null, $this->_auth->getAuthenticationID());
        $this->assertEquals(null, $this->_auth->getAuthenticationDataContainer());
    }
}