<?php

require_once 'Tests/testsetupdb.php';

require_once 'Tests/dummies/authentication/resource.php';
require_once 'Tests/dummies/cookie.php';


class AuthenticationChainCookieTest extends Chrome_TestCase
{
    protected $_chain = null;

    protected $_cookie;

    protected $_options = array(
        'cookie_namespace' => '_AUTH_TEST',
        'cookie_renew_probability' => 1,
        //'cookie_instance'          => Chrome_Cookie_Dummy::getInstance(),
        );

    protected $_model = null;

    public function setUp()
    {
        if($this->_model === null) {
            $this->_model = new Chrome_Model_Authentication_Cookie($this->_appContext->getModelContext(), array());
        }

        if(!isset($this->_options['cookie_instance']) OR $this->_options['cookie_instance'] !== false) {
            $this->_cookie = new Chrome_Cookie_Dummy();
            $this->_options['cookie_instance'] = $this->_cookie;
        } else {
            $this->_cookie = $this->_appContext->getRequestHandler()->getRequestData()->getCookie();
        }


        $this->_chain = new Chrome_Authentication_Chain_Cookie($this->_model, $this->_cookie, $this->_options);
        $this->_chain->setChain(new Chrome_Authentication_Chain_Null());
    }

    public function testUpdate()
    {
        $id = mt_rand(1, 100);

        $container = new Chrome_Authentication_Data_Container(__class__);
        $container->setID($id);
        $container->setAutoLogin(true);

        $this->_chain->update($container);

        if($this->_options['cookie_instance'] !== null) {
            $this->assertArrayHasKey($this->_options['cookie_namespace'], $this->_options['cookie_instance']->_cookie);
        } else {
            $this->assertTrue($this->_cookie->offsetExists($this->_options['cookie_namespace']));
        }

        $this->_chain->update($container);

        if($this->_options['cookie_instance'] !== null) {
            $this->assertArrayHasKey($this->_options['cookie_namespace'], $this->_options['cookie_instance']->_cookie);
        } else {
            $this->assertTrue(Chrome_Cookie::getInstance()->offsetExists($this->_options['cookie_namespace']));
        }
    }

    /**
     * Person should not get authenticated, because we're using a resource and cookie shall not support that!
     */
    public function testAuthenticateWithResource()
    {
        $resource = new Chrome_Authentication_Resource_Dummy();
        $resource->_id = mt_rand(1, 100);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertFalse($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));
        $this->assertNotEquals('Chrome_Authentication_Chain_Cookie', $authContainer->getAuthenticatedBy());
    }

    /**
     * @depends testUpdate
     */
    public function testAuthenticateWithoutResource()
    {
        $id = 1;

        $container = new Chrome_Authentication_Data_Container(__class__);
        $container->setID($id);
        $container->setAutoLogin(true);

        $this->_chain->update($container);

        $authContainer = $this->_chain->authenticate();

        $this->assertTrue($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));
        $this->assertEquals('Chrome_Authentication_Chain_Cookie', $authContainer->getAuthenticatedBy());
        $this->assertEquals($id, $authContainer->getID());
    }

    /**
     * @depends testAuthenticateWithoutResource
     */
    public function testAuthenticateWithoutResourceAndWrongUserInput()
    {
        $this->_options['cookie_instance']->setCookie($this->_options['cookie_namespace'], $this->_model->encodeCookieString(12, 'anyStringWith\''));

        $authContainer = $this->_chain->authenticate();

        $this->assertFalse($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));


        $this->_options['cookie_instance']->setCookie($this->_options['cookie_namespace'], $this->_model->encodeCookieString(null, 'anyToken'));

        $authContainer = $this->_chain->authenticate();

        $this->assertFalse($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));



        $this->_options['cookie_instance']->setCookie($this->_options['cookie_namespace'], $this->_model->encodeCookieString(123456, null));

        $authContainer = $this->_chain->authenticate();

        $this->assertFalse($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));



        // this id should not exist 123456789
        $this->_options['cookie_instance']->setCookie($this->_options['cookie_namespace'], $this->_model->encodeCookieString(123456789, 'anyToken'));

        $authContainer = $this->_chain->authenticate();

        $this->assertFalse($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER), 'maybe id exists?');
    }

    /**
     * @depends testUpdate
     */
    public function testAuthenticateWithDefaultCookieInterface() {
        $this->_options['cookie_instance'] = null;
        $this->setUp();
        $this->testUpdate();
    }

    public function testCreateAuthentication() {
        $resource = new Chrome_Authentication_Create_Resource_Dummy();

        // does nothing
        $this->_chain->createAuthentication($resource);
    }

    public function testGetChain() {

        $chain = new Chrome_Authentication_Chain_Null();
        $this->_chain->addChain($chain);

        $this->assertSame($chain, $this->_chain->getChain());
    }
}
