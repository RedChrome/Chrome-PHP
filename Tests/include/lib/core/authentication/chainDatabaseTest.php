<?php

require_once 'Tests/testsetup.php';
require_once 'Tests/dummies/authentication/resource.php';

class AuthenticationChainDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected $_chain = null;

    protected $_model = null;

    protected $_updateTime = false;

    protected $_setTime = false;

    public function setUp()
    {
        if($this->_model === null) {
            $this->_model = new Chrome_Model_Authentication_Database();
        }

        $this->_chain = new Chrome_Authentication_Chain_Database($this->_model, $this->_updateTime, $this->_setTime);

        $this->_chain->setChain(new Chrome_Authentication_Chain_Null());
        $this->_chain->addChain(new Chrome_Authentication_Chain_Null());
    }

    /**
     * Database cannot authenticate without resource...
     */

    public function testAuthenticateWithoutResource()
    {
        $authContainer = $this->_chain->authenticate();

        $this->assertTrue($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_GUEST));
        $this->assertNotEquals('Chrome_Authentication_Chain_Database', $authContainer->getAuthenticatedBy());

    }

    public function testAuthenticateWithWrongResource()
    {
        $resource = new Chrome_Authentication_Resource_Dummy();

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertTrue($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_GUEST));
        $this->assertNotEquals('Chrome_Authentication_Chain_Database', $authContainer->getAuthenticatedBy());
    }

    public function testAuthenticateWithProperResourceButNoData()
    {
        $resource = new Chrome_Authentication_Resource_Database(0, 0, 0);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertFalse($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));
        $this->assertNotEquals('Chrome_Authentication_Chain_Database', $authContainer->getAuthenticatedBy());
    }

    public function testAuthenticateWithProperResourceButWrongData()
    {
        $resource = new Chrome_Authentication_Resource_Database(123456789, 0, 0);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertFalse($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));
        $this->assertNotEquals('Chrome_Authentication_Chain_Database', $authContainer->getAuthenticatedBy());

        // user with id 2 exists...
        $resource = new Chrome_Authentication_Resource_Database(2, 'wrongPW', 0);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertTrue($this->_model->getPasswordAndSaltByIdentity(2) !== false, 'user with id 2 does not exist, run setupdb!');
        $this->assertFalse($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));
        $this->assertNotEquals('Chrome_Authentication_Chain_Database', $authContainer->getAuthenticatedBy());
    }

    public function testAuthenticateWithProperResourceAndRightData()
    {
        $resource = new Chrome_Authentication_Resource_Database(2, 'test', 0);

        $authContainer = $this->_chain->authenticate($resource);

        $this->assertTrue($authContainer->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_USER));
        $this->assertEquals('Chrome_Authentication_Chain_Database', $authContainer->getAuthenticatedBy());

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
        $resource = new Chrome_Authentication_Create_Resource_Database('myName', 'anyPass', 'anySalt');

        $this->_chain->createAuthentication($resource);

        $this->assertNotNull($resource->getID());
        $this->assertEquals('myName', $resource->getIdentity());
    }

    public function testCreateAuthenticationWithoutSalt()
    {
        $resource = new Chrome_Authentication_Create_Resource_Database('myName', 'myPassword WIthout a satl');
        $this->_chain->createAuthentication($resource);

        $this->assertNotNull($resource->getID());
    }
}
