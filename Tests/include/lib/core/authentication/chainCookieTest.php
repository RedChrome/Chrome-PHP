<?php

class AuthenticationChainCookieTest extends Chrome_TestCase
{
    protected $_chain = null;

    protected $_cookie;

    protected $_options = array(
        'cookie_namespace' => '_AUTH_TEST',
        'cookie_renew_probability' => 1,
        );

    protected $_model = null;

    protected $_resetCookie = true;

    public function setUp()
    {
        if($this->_model === null) {
            $this->_model = new \Chrome\Model\Authentication\Cookie($this->_appContext->getModelContext()->getDatabaseFactory(), $this->_diContainer->get('\Chrome_Model_Database_Statement_Interface'));
        }

        if($this->_resetCookie === true OR !($this->_cookie instanceof \Chrome\Request\Cookie_Interface) ) {
            $this->_cookie = new \Test\Chrome\Request\Cookie\Dummy();
        }

        $this->_resetCookie = true;


        $this->_chain = new \Chrome\Authentication\Chain\CookieChain($this->_model, $this->_cookie, $this->_diContainer->get('\Chrome\Hash\Hash_Interface'));
        $this->_chain->setOptions($this->_options);
        $this->_chain->setChain(new \Chrome\Authentication\Chain\NullChain());
    }

    public function testUpdate()
    {
        $id = mt_rand(1, 100);

        $container = new \Chrome\Authentication\Container(__class__);
        $container->setID($id);
        $container->setAutoLogin(true);

        $this->_chain->update($container);

        $this->assertArrayHasKey($this->_options['cookie_namespace'], $this->_cookie->_cookie);

        $this->_chain->update($container);

        $this->assertArrayHasKey($this->_options['cookie_namespace'], $this->_cookie->_cookie);
    }

    /**
     * Person should not get authenticated, because we're using a resource and cookie shall not support that!
     */
    public function testAuthenticateWithResource()
    {
        $resource = new \Test\Chrome\Authentication\Resource\Dummy();
        $resource->_id = mt_rand(1, 100);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertNotEquals('Chrome\Authentication\Chain\CookieChain', $authContainer->getAuthenticatedBy());
    }

    /**
     * @depends testUpdate
     */
    public function testAuthenticateWithoutResource()
    {
        $authContainer = $this->_chain->authenticate();
        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));

        $id = 1;

        $container = new \Chrome\Authentication\Container(__class__);
        $container->setID($id);
        $container->setAutoLogin(true);

        $this->_chain->update($container);

        $authContainer = $this->_chain->authenticate();

        $this->assertTrue($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertEquals('Chrome\Authentication\Chain\CookieChain', $authContainer->getAuthenticatedBy());
        $this->assertEquals($id, $authContainer->getID());
    }

    public function testAuthenticateWithNotWorkingModel()
    {
        // since we're using a null model, the cookie chain cannot ensure whether the cookie is valid or not
        // -> cannot authenticate

        $this->_model = new \Test\Chrome\Model\NullModel();

        $this->setUp();

        $authContainer = $this->_chain->authenticate();
        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));

        $id = 1;

        $container = new \Chrome\Authentication\Container(__class__);
        $container->setID($id);
        $container->setAutoLogin(true);

        $this->_chain->update($container);

        $authContainer = $this->_chain->authenticate();

        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertEquals('Chrome\Authentication\Chain\CookieChain', $authContainer->getAuthenticatedBy());
    }

    /**
     * @depends testAuthenticateWithoutResource
     */
    public function testAuthenticateWithoutResourceAndWrongUserInput()
    {
        $this->testAuthenticateWithoutResource();

        $cookie = clone $this->_cookie;
        $cookie2 = clone $this->_cookie;

        $this->_chain->deAuthenticate();

        $authContainer = $this->_chain->authenticate();
        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));

        $this->_cookie = $cookie;
        $this->_resetCookie = false;
        $this->setUp();

        $authContainer = $this->_chain->authenticate();
        $this->assertTrue($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));

        // set the cookie string to something unexpected.
        $cookie2->_cookie[$this->_options['cookie_namespace']] = ')&%TBFEHFG&A/()QB§';

        $this->_cookie = $cookie2;
        $this->_resetCookie = false;
        $this->setUp();
        $authContainer = $this->_chain->authenticate();
        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));

        // this id does NOT exist
        $id = 8312471782;

        $container = new \Chrome\Authentication\Container(__class__);
        $container->setID($id);
        $container->setAutoLogin(true);

        $this->_chain->update($container);

        $authContainer = $this->_chain->authenticate();

        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
    }

    /**
     * @depends testUpdate
     */
    public function testAuthenticateWithDefaultCookieInterface() {
        $this->_options['cookie_instance'] = $this->_appContext->getRequestHandler()->getRequestData()->getCookie();
        $this->_resetCookie = false;
        $this->setUp();
        $this->testUpdate();
    }

    public function testCreateAuthentication() {
        $resource = new \Test\Chrome\Authentication\Resource\Create_Dummy();

        // does nothing
        $this->_chain->createAuthentication($resource);
    }

    public function testGetChain() {

        $chain = new \Chrome\Authentication\Chain\NullChain();
        $this->_chain->addChain($chain);

        $this->assertSame($chain, $this->_chain->getChain());
    }

    public function testCookieModel()
    {
        $this->_model->setOptions(array('dbTable' => 'authenticate_'.mt_rand(0, 1000)));

        // if the string is not escaped, then the token is assumed to be invalid -> model returns null
        $this->assertNull($this->_model->doesIdAndTokenExist(mt_rand(0, 100), 'nH/R=§NDA)Q"§?REH!QN'));
    }
}
