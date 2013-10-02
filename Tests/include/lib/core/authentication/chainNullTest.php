<?php

require_once 'Tests/dummies/authentication/resource.php';

class AuthenticationChainNullTest extends PHPUnit_Framework_TestCase
{
    protected $_chain = null;

    public function setUp()
    {
        $this->_chain = new Chrome_Authentication_Chain_Null();
    }

    public function testAuthenticate()
    {
        $resource = new Chrome_Authentication_Resource_Dummy();
        $resource->_id = mt_rand(1, 100);

        $result = $this->_chain->authenticate($resource);

        $this->assertTrue($result->hasStatus(Chrome_Authentication_Data_Container_Interface::STATUS_GUEST));
        $this->assertEquals(1, $result->getID());

    }

    public function testUpdate()
    {
        $resource = new Chrome_Authentication_Data_Container(__class__);

        // does nothing
        $this->_chain->update($resource);
    }

    public function testAddChain()
    {
        $addedChain = new Chrome_Authentication_Chain_Null();

        $this->assertSame($addedChain, $this->_chain->addChain($addedChain));
    }

    public function testCreateAuthentication()
    {
        $resource = new Chrome_Authentication_Create_Resource_Dummy();
        $resource->_id = mt_rand(1, 100);

        $this->_chain->createAuthentication($resource);

    }

    public function testDeAuthenticate()
    {
        $this->_chain->deAuthenticate();
    }

}
