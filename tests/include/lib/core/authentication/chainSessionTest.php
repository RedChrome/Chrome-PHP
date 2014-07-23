<?php

namespace Test\Chrome\Authentication\Chain;

class SessionTest extends \Test\Chrome\TestCase
{
    protected $_chain = null;

    protected $_options = array('session_namespace' => '_AUTH_SESSION_TEST');

    protected $_session;

    public function setUp()
    {
        $this->_chain = new \Chrome\Authentication\Chain\SessionChain($this->_session, $this->_options);
        $this->_chain->setChain(new \Chrome\Authentication\Chain\NullChain());
    }

    public function testAuthenticateWithResource()
    {
        $resource = new \Test\Chrome\Authentication\Resource\Dummy();

        $container = $this->_chain->authenticate($resource);

        // nothing is saved in session, so the person should be a guest
        $this->assertFalse($container->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertTrue($container->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_GUEST));
    }

    public function testAuthenticateWithoutResource()
    {
        $container = $this->_chain->authenticate();

        // nothing is saved in session, so the person should be a guest
        $this->assertFalse($container->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertTrue($container->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_GUEST));
    }

    public function testUseCaseAuthenticationUsingSession()
    {
        $id = mt_rand(1, 100);

        $container = new \Chrome\Authentication\Container(__class__);
        $container->setID($id);

        $this->_chain->update($container);

        $authContainer = $this->_chain->authenticate();

        $this->assertEquals('Chrome\Authentication\Chain\SessionChain', $authContainer->getAuthenticatedBy());
        $this->assertEquals($id, $authContainer->getID());
        $this->assertTrue($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertTrue($this->_session->offsetExists($this->_options['session_namespace']));

        $this->_chain->deAuthenticate();

        // after deAuthentication, user should not get authenticated again
        // first we need to call update again...
        $authContainer = $this->_chain->authenticate();

        $this->assertNotEquals('Chrome\Authentication\Chain\SessionChain', $authContainer->getAuthenticatedBy());
        $this->assertNotEquals($id, $authContainer->getID());
        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertFalse($this->_session->offsetExists($this->_options['session_namespace']));
    }

    public function testUseCaseAuthenticationWithResourceUsingSession()
    {
        // session should do nothing, because we're authenticating with a resource
        // and session does not support that! (with reason)

        $id = mt_rand(2, 100);

        $container = new \Chrome\Authentication\Container(__class__);
        $container->setID($id);

        $this->_chain->update($container);

        $resource = new \Test\Chrome\Authentication\Resource\Dummy();

        $container = $this->_chain->authenticate($resource);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertNotEquals('Chrome\Authentication\Chain\SessionChain', $authContainer->getAuthenticatedBy());
        $this->assertNotEquals($id, $authContainer->getID());
        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertTrue($this->_session->offsetExists($this->_options['session_namespace']));
    }

    public function testCreateAuthenticationUsingSession()
    {
        $resource = new \Test\Chrome\Authentication\Resource\Create_Dummy();
        $resource->_id = null;
        // should do nothing
        $this->_chain->createAuthentication($resource);
        $this->assertNull($resource->_id);
    }

}
