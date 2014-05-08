<?php

require_once 'tests/dummies/authentication/resource.php';
require_once 'tests/dummies/authentication/chain.php';
require_once 'tests/dummies/cookie.php';

class AuthenticationTest extends PHPUnit_Framework_TestCase
{
    protected $_auth = null;

    public function setUp()
    {
        $this->_auth = new \Chrome\Authentication\Authentication();
    }

    public function tearDown()
    {

    }

    public function testGetChain()
    {
        // every authentication object has to have at least one chain
        $this->assertTrue($this->_auth->getChain() instanceof \Chrome\Authentication\Chain\Chain_Interface);

        $chain = $this->getMock('\Chrome\Authentication\Chain\Chain_Interface');
        $this->_auth->setChain($chain);
        $this->assertSame($chain, $this->_auth->getChain());
    }

    public function testAddChain()
    {
        $firstChain  = $this->getMock('\Chrome\Authentication\Chain\Chain_Interface');
        $secondChain = $this->getMock('\Chrome\Authentication\Chain\Chain_Interface');

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
        $resource = $this->getMock('\Chrome\Authentication\CreateResource_Interface');

        $this->_auth->setExceptionHandler($this->getMock('\Chrome\Exception\Handler_Interface'));
        $exceptionHandler = $this->getMock('\Chrome\Exception\Handler_Interface');
        $this->_auth->setExceptionHandler($exceptionHandler);
        $this->assertSame($exceptionHandler, $this->_auth->getExceptionHandler());

        // throws a exception while creating authentication
        $chain = $this->getMock('Chrome\Authentication\Chain\Chain_Interface');
        $chain->expects($this->any())->method('createAuthentication')->will($this->throwException(new \Chrome\AuthenticationException()));
        // this will throw an exception, but it is caught in the exception handler
        $this->_auth->setChain($chain)->createAuthentication($resource);

        // this will not throw an exception
        $this->_auth->setChain($this->getMock('Chrome\Authentication\Chain\Chain_Interface'))->createAuthentication($resource);

        $this->_auth->setChain($chain);
    }

    public function testExceptionHandlerWhileAuthenticaing()
    {
        $resource = $this->getMock('\Chrome\Authentication\Resource_Interface');
        $this->_auth->setExceptionHandler($this->getMock('\Chrome\Exception\Handler_Interface'));

        $chain = $this->getMock('\Chrome\Authentication\Chain\Chain_Interface');
        $chain->expects($this->any())->method('authenticate')->will($this->throwException(new \Chrome\AuthenticationException()));

        $this->_auth->setChain($chain)->authenticate($resource);
    }

    /**
     * @depends testExceptionHandlerWhileAuthenticaing
     */
    public function testExceptionHandlerWhileAuthenticaingWithError()
    {
        $resource = $this->getMock('\Chrome\Authentication\Resource_Interface');

        // do not set an exception handler, this will give us the exception
        $this->setExpectedException('\Chrome\AuthenticationException');

        $chain = $this->getMock('\Chrome\Authentication\Chain\Chain_Interface');
        $chain->expects($this->any())->method('authenticate')->will($this->throwException(new \Chrome\AuthenticationException()));

        $this->_auth->setChain($chain)->authenticate($resource);
    }

    public function testAuthenticateCouldNotAuthenticate()
    {
        $chain = $this->getMock('\Chrome\Authentication\Chain\Chain_Interface');
        $this->_auth->addChain($chain);

        $this->setExpectedException('\Chrome\AuthenticationException');

        $this->_auth->authenticate();
    }

    public function testAuthenticateHandlesWrongIDs()
    {
        $chain = $this->getMock('\Chrome\Authentication\Chain\Chain_Interface');
        $chain->expects($this->any())->method('authenticate')->will($this->returnValue('1f921'));
        $this->_auth->addChain($chain);

        $this->setExpectedException('\Chrome\AuthenticationException');

        $this->_auth->authenticate();
    }

    public function testAuthenticateSuccessfull()
    {
        $id = mt_rand(1, 100);

        $container = $this->getMock('\Chrome\Authentication\Container_Interface');
        $container->expects($this->any())->method('getID')->will($this->returnValue($id));
        $container->expects($this->any())->method('getStatus')->will($this->returnValue(\Chrome\Authentication\Container_Interface::STATUS_USER));

        $chain = $this->getMock('\Chrome\Authentication\Chain\Chain_Interface');
        $chain->expects($this->any())->method('authenticate')->will($this->returnValue($container));

        $this->_auth->addChain($chain);

        $this->_auth->authenticate();

        $this->assertEquals($id, $this->_auth->getAuthenticationID());
        $this->assertTrue($this->_auth->isAuthenticated());
        $this->assertTrue($this->_auth->getAuthenticationDataContainer() instanceof \Chrome\Authentication\Container_Interface);
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