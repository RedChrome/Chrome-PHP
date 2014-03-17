<?php

require_once 'Tests/dummies/authentication/resource.php';

class AuthenticationChainNullTest extends PHPUnit_Framework_TestCase
{
    protected $_chain = null;

    public function setUp()
    {
        $this->_chain = new \Chrome\Authentication\Chain\NullChain();
    }

    public function testAuthenticate()
    {
        $resource = new \Test\Chrome\Authentication\Resource\Dummy();
        $resource->_id = mt_rand(1, 100);

        $result = $this->_chain->authenticate($resource);

        $this->assertTrue($result->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_GUEST));
        $this->assertEquals(0, $result->getID());

    }

    public function testUpdate()
    {
        $resource = new \Chrome\Authentication\Container(__class__);

        // does nothing
        $this->_chain->update($resource);
    }

    public function testAddChain()
    {
        $addedChain = new \Chrome\Authentication\Chain\NullChain();

        $this->assertSame($addedChain, $this->_chain->addChain($addedChain));
    }

    public function testCreateAuthentication()
    {
        $resource = new \Test\Chrome\Authentication\Resource\Create_Dummy();
        $resource->_id = mt_rand(1, 100);

        $this->_chain->createAuthentication($resource);

    }

    public function testDeAuthenticate()
    {
        $this->_chain->deAuthenticate();
    }

}
