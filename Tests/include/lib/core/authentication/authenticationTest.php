<?php

require_once 'Tests/testsetup.php';

require_once 'Tests/dummies/authentication/resource.php';
require_once 'Tests/dummies/authentication/chain.php';
require_once 'Tests/dummies/authentication/fail.php';
require_once 'Tests/dummies/cookie.php';

class AuthenticationTest extends PHPUnit_Framework_TestCase
{
    protected $_auth = null;

    public function setUp() {
        $this->_auth = new Chrome_Authentication();
    }

    public function tearDown() {

    }

    public function testGetChain()
    {
        // every authentication object has to have at least one chain
        $this->assertTrue($this->_auth->getChain() instanceof Chrome_Authentication_Chain_Interface);
        //$this->assertTrue($this->_auth->getChain()->getChain() instanceof Chrome_Authentication_Chain_Interface);

        $chain = new Chrome_Authentication_Chain_Null();

        $this->_auth->setChain($chain);

        $this->assertSame($chain, $this->_auth->getChain());
    }

    public function testAddChain() {

        $chain = $this->_auth->getChain();

        $this->_auth->setChain(new Chrome_Authentication_Chain_Wrapper());
        $this->_auth->addChain(new Chrome_Authentication_Chain_Wrapper());

        $this->assertTrue($this->_auth->getChain() instanceof Chrome_Authentication_Chain_Wrapper);

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
        $resource = new Chrome_Authentication_Create_Resource_Dummy();


        $exceptionHandler = new Chrome_Exception_Handler_Dummy();
        $this->_auth->setExceptionHandler($exceptionHandler);
        $this->assertSame($exceptionHandler, $this->_auth->getExceptionHandler());

        // throws a exception while creating authentication
        $this->_auth->setChain(new Chrome_Authentication_Chain_Wrapper(true))->createAuthentication($resource);

        $this->_auth->setChain(new Chrome_Authentication_Chain_Wrapper(false))->createAuthentication($resource);


        $this->_auth->setChain($chain);
    }

    public function testAuthenticateCouldNotAuthenticate()
    {
        $chain = new Chrome_Authentication_Chain_Fail();
        $this->_auth->addChain($chain);

        $this->setExpectedException('Chrome_Exception_Authentication');

        $this->_auth->authenticate();
    }

    public function testAuthenticateHandlesWrongIDs()
    {
        $chain = new Chrome_Authentication_Chain_Fail();
        $this->_auth->addChain($chain);
        $chain->_id = '1f921';

        $this->setExpectedException('Chrome_Exception_Authentication');

        $this->_auth->authenticate();
    }

    public function testAuthenticateSuccessfull()
    {
        $chain = new Chrome_Authentication_Chain_Fail();
        $this->_auth->addChain($chain);
        $chain->_id = 1;

        $this->_auth->authenticate();

        $this->assertEquals(1, $this->_auth->getAuthenticationID());
        $this->assertTrue($this->_auth->isAuthenticated());
        $this->assertTrue($this->_auth->getAuthenticationDataContainer() instanceof Chrome_Authentication_Data_Container_Interface);
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