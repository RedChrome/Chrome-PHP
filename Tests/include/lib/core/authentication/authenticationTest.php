<?php

require_once 'Tests/dummies/authentication/resource.php';
require_once 'Tests/dummies/authentication/chain.php';
require_once 'Tests/dummies/cookie.php';

class AuthenticationTest extends PHPUnit_Framework_TestCase
{
    protected $_auth = null;

    public function setUp() {
        $this->_auth = new \Chrome\Authentication\Authentication();
    }

    public function tearDown() {

    }

    public function testGetChain()
    {
        // every authentication object has to have at least one chain
        $this->assertTrue($this->_auth->getChain() instanceof \Chrome\Authentication\Chain\Chain_Interface);
        //$this->assertTrue($this->_auth->getChain()->getChain() instanceof Chrome_Authentication_Chain_Interface);

        $chain = new \Chrome\Authentication\Chain\NullChain();

        $this->_auth->setChain($chain);

        $this->assertSame($chain, $this->_auth->getChain());
    }


    public function testAddChain() {

        $chain = $this->_auth->getChain();

        $this->_auth->setChain(new \Test\Chrome\Authentication\Chain\WrapperChain());
        $this->_auth->addChain(new \Test\Chrome\Authentication\Chain\WrapperChain());

        $this->assertTrue($this->_auth->getChain() instanceof \Test\Chrome\Authentication\Chain\WrapperChain);

        $this->_auth->setChain($chain);
    }


    public function testAuthenticateWithNoInfo() {

        $this->_auth->authenticate();

        $this->assertFalse($this->_auth->isUser());
        $this->assertTrue($this->_auth->isAuthenticated());
    }

    /*
    public function testAuthenticate() {

        $chain = $this->_auth->getChain();

        $id = mt_rand(1, 100);

        $container = new Chrome_Authentication_Data_Container(__class__);
        $container->setID($id);

        $chain->update($container);

        $this->_auth->authenticate();

        $this->assertFalse($this->_auth->isUser());
        $this->assertTrue($this->_auth->isAuthenticated());
        $this->assertEquals($id, $this->_auth->getAuthenticationID());


        $this->_auth->deAuthenticate();
        $this->assertFalse($this->_auth->isUser());
        $this->assertFalse($this->_auth->isAuthenticated());

        $this->testAuthenticateWithNoInfo();
    }*/

    public function testCreateAuthentication() {

        $chain = $this->_auth->getChain();
        $resource = new \Test\Chrome\Authentication\Resource\Create_Dummy();


        $this->_auth->setExceptionHandler(new Chrome_Exception_Handler_Dummy());
        $exceptionHandler = new Chrome_Exception_Handler_Dummy();
        $this->_auth->setExceptionHandler($exceptionHandler);
        $this->assertSame($exceptionHandler, $this->_auth->getExceptionHandler());

        // throws a exception while creating authentication
        $this->_auth->setChain(new \Test\Chrome\Authentication\Chain\WrapperChain(true))->createAuthentication($resource);

        $this->_auth->setChain(new \Test\Chrome\Authentication\Chain\WrapperChain(false))->createAuthentication($resource);


        $this->_auth->setChain($chain);
    }

    public function testExceptionHandlerWhileAuthenticaing()
    {
        $chain = $this->_auth->getChain();
        $resource = new \Test\Chrome\Authentication\Resource\Dummy();
        $this->_auth->setExceptionHandler(new Chrome_Exception_Handler_Dummy());

        $this->_auth->setChain(new \Test\Chrome\Authentication\Chain\WrapperChain(false, true))->authenticate($resource);
    }

    /**
     * @depends testExceptionHandlerWhileAuthenticaing
     */
    public function testExceptionHandlerWhileAuthenticaingWithError()
    {
        $chain = $this->_auth->getChain();
        $resource = new \Test\Chrome\Authentication\Resource\Dummy();

        // do not set an exception handler, this will give us the exception
        //$this->_auth->setExceptionHandler(new Chrome_Exception_Handler_Dummy());
        $this->setExpectedException('\Chrome_Exception_Authentication');
        $this->_auth->setChain(new \Test\Chrome\Authentication\Chain\WrapperChain(false, true))->authenticate($resource);
    }

    public function testAuthenticateCouldNotAuthenticate()
    {
        $chain = new \Test\Chrome\Authentication\Chain\FailChain();
        $this->_auth->addChain($chain);

        $this->setExpectedException('\Chrome_Exception_Authentication');

        $this->_auth->authenticate();
    }

    public function testAuthenticateHandlesWrongIDs()
    {
        $chain = new \Test\Chrome\Authentication\Chain\FailChain();
        $this->_auth->addChain($chain);
        $chain->_id = '1f921';

        $this->setExpectedException('\Chrome_Exception_Authentication');

        $this->_auth->authenticate();
    }

    public function testAuthenticateSuccessfull()
    {
        $chain = new \Test\Chrome\Authentication\Chain\FailChain();
        $this->_auth->addChain($chain);
        $chain->_id = 1;

        $this->_auth->authenticate();

        $this->assertEquals(1, $this->_auth->getAuthenticationID());
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