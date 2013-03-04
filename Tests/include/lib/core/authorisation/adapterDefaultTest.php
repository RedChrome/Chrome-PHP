<?php

require_once 'Tests/testsetupmodules.php';

class AuthorisationAdapterDefaultTest extends PHPUnit_Framework_TestCase
{

    protected $_adapter = null;

    // strcuture: userid,resourceid, resource_trafo, result
    protected $_array = array(
        array(0, 'testIsAllowed', 'guestAllowed', true),
        array(0, 'testIsAllowed', 'guestNotAllowed', false),
        array(1, 'testIsAllowed', 'guestAllowed', false),
        array(1, 'testIsAllowed', 'guestNotAllowed', true),
        array(2, 'testIsAllowed', 'guestAllowed', true),
        array(2, 'testIsAllowed', 'guestNotAllowed', true),
        array(3, 'testIsAllowed', 'guestAllowed', true),
        array(3, 'testIsAllowed', 'guestNotAllowed', true)
        );

    public function setUp() {
        $this->_adapter = Chrome_Authorisation_Adapter_Default::getInstance();
        $this->_adapter->setModel(new Chrome_Model_Authorisation_Default_DB());
    }

    public function testSetDataContainer() {

        $container = new Chrome_Authentication_Data_Container(__class__);
        $container->setID(126546224769764);

        $this->_adapter->setDataContainer($container);
    }

    public function testGetGroupId() {

        $container = new Chrome_Authentication_Data_Container(__class__);
        $container->setID(0);

        $this->_adapter->setDataContainer($container);

        $this->assertEquals(1, $this->_adapter->getGroupId());

        $container->setID(1);
        $this->_adapter->setDataContainer($container);

        $this->assertEquals(123456, $this->_adapter->getGroupId());
    }

    public function testIsAllowed() {

        $container = new Chrome_Authentication_Data_Container(__class__);

        foreach($this->_array as $key => $array) {
            $container->setID($array[0]);
            $this->_adapter->setDataContainer($container);

            $resource = new Chrome_Authorisation_Resource($array[1], $array[2]);

            if($array[3] === true) {
                $this->assertTrue($this->_adapter->isAllowed($resource), 'error while trying key '.($key+1));
            } else {
                $this->assertFalse($this->_adapter->isAllowed($resource), 'error while trying key '.($key+1));
            }

        }
    }




}

class AuthorisationAdapterDefaultModelDefaultTest extends PHPUnit_Framework_TestCase
{
    protected $_model = null;

    public function setUp() {
        $this->_model = new Chrome_Model_Authorisation_Default_DB();
    }

    public function testGetAccessById() {
        // just random numbers
        $this->assertEquals(1234666, $this->_model->getAccessById('test', 'read'));
        $this->assertEquals(913785, $this->_model->getAccessById('test', 'write'));
        $this->assertEquals(18462, $this->_model->getAccessById('test2', 'anyTrafo'));
        $this->assertEquals(0, $this->_model->getAccessById('test', 'notExisting'));
        $this->assertEquals(0, $this->_model->getAccessById('test2', 'notExisting'));
    }

    public function testGetUserGroupById() {
        // just random numbers
        $this->assertEquals(1, $this->_model->getUserGroupById(0));
        $this->assertEquals(123456, $this->_model->getUserGroupById(1));
        $this->assertEquals(89123, $this->_model->getUserGroupById(2));
        $this->assertEquals(8388607, $this->_model->getUserGroupById(3));
        $this->assertEquals(168804, $this->_model->getUserGroupById(4));

    }

}