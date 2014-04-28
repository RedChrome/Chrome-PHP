<?php

class AuthenticationChainDatabaseTest extends Chrome_TestCase
{
    protected $_chain = null;

    protected $_model = null;

    protected $_updateTime = false;

    protected $_setTime = false;

    public function setUp()
    {
        if($this->_model === null) {
            $this->_model = new \Chrome\Model\Authentication\Database($this->_diContainer->get('Chrome\Database\Factory\Factory_Interface'), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface'));
        }

        $this->_chain = new \Chrome\Authentication\Chain\DatabaseChain($this->_model, $this->_updateTime, $this->_setTime);

        $this->_chain->setChain(new \Chrome\Authentication\Chain\NullChain());
        $this->_chain->addChain(new \Chrome\Authentication\Chain\NullChain());
    }

    /**
     * Database cannot authenticate without resource...
     */

    public function testAuthenticateWithoutResource()
    {
        $authContainer = $this->_chain->authenticate();

        $this->assertTrue($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_GUEST));
        $this->assertNotEquals('Chrome\Authentication\Chain\DatabaseChain', $authContainer->getAuthenticatedBy());

    }

    public function testAuthenticateWithWrongResource()
    {
        $resource = new \Test\Chrome\Authentication\Resource\Dummy();

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertTrue($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_GUEST));
        $this->assertNotEquals('Chrome\Authentication\Chain\DatabaseChain', $authContainer->getAuthenticatedBy());
    }

    public function testAuthenticateWithProperResourceButNoData()
    {
        $resource = new \Chrome\Authentication\Resource\Database(0, 0, 0);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertNotEquals('Chrome\Authentication\Chain\DatabaseChain', $authContainer->getAuthenticatedBy());
    }

    public function testAuthenticateWithProperResourceButWrongData()
    {
        $resource = new \Chrome\Authentication\Resource\Database(123456789, 0, 0);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertNotEquals('Chrome\Authentication\Chain\DatabaseChain', $authContainer->getAuthenticatedBy());

        // user with id 2 exists...
        $resource = new \Chrome\Authentication\Resource\Database(1, 'wrongPW', 0);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertTrue($this->_model->getPasswordAndSaltByIdentity(1) !== false, 'user with id 1 does not exist, run setupdb!');
        $this->assertFalse($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertNotEquals('Chrome\Authentication\Chain\DatabaseChain', $authContainer->getAuthenticatedBy());
    }

    public function testAuthenticateWithProperResourceAndRightData()
    {
        $resource = new \Chrome\Authentication\Resource\Database(1, 'test', 0);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertTrue($authContainer->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertEquals('Chrome\Authentication\Chain\DatabaseChain', $authContainer->getAuthenticatedBy());

        return $authContainer;
    }

    public function testAuthenticateWithProperResourceAndRightDataAndSetTime()
    {
        $this->_setTime = true;
        $this->_updateTime = true;
        $this->setUp();
        $container = $this->testAuthenticateWithProperResourceAndRightData();
        $this->_chain->update($container);
        $this->_chain->deAuthenticate();
    }

    public function testCreateAuthenticationWithGivenSalt()
    {
        $resource = new \Chrome\Authentication\Resource\Create_Database('myName', 'anyPass', 'anySalt');

        $this->_chain->createAuthentication($resource);

        $this->assertNotNull($resource->getID());
        $this->assertEquals('myName', $resource->getIdentity());
    }

    public function testCreateAuthenticationWithoutSalt()
    {
        $resource = new \Chrome\Authentication\Resource\Create_Database('myName', 'myPassword WIthout a satl');
        $this->_chain->createAuthentication($resource);

        $this->assertNotNull($resource->getID());
    }
}
