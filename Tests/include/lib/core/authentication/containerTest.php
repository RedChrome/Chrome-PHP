<?php


class authenticationContainerTest extends PHPUnit_Framework_TestCase
{
    protected $_container = null;

    public function setUp()
    {

        $this->_container = new \Chrome\Authentication\Container(__CLASS__);
    }

    public function testSetId()
    {
        $id = mt_rand(1, 1000);

        $this->_container->setID($id);
        $this->assertEquals($id, $this->_container->getID());
    }

    public function testSetAutoLogin()
    {
        $bool = (bool) mt_rand(0, 1);

        $this->_container->setAutoLogin($bool);
        $this->assertEquals($bool, $this->_container->getAutoLogin());

        // only accept booleans
        $this->_container->setAutoLogin(1);
        $this->assertFalse($this->_container->getAutoLogin());
    }

    public function testStatus()
    {
        $this->_container->setStatus(\Chrome\Authentication\Container_Interface::STATUS_USER);

        $this->assertEquals(\Chrome\Authentication\Container_Interface::STATUS_USER, $this->_container->getStatus());
        $this->assertTrue($this->_container->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_USER));
        $this->assertFalse($this->_container->hasStatus(\Chrome\Authentication\Container_Interface::STATUS_GUEST));
    }

    public function testAuthenticatedBy()
    {
        $this->assertEquals(__CLASS__, $this->_container->getAuthenticatedBy());
    }
}
