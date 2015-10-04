<?php

namespace Test\Chrome\Authorisation\Adapter;

class SimpleTest extends \Test\Chrome\TestCase
{
    protected $_adapter = null;

    public function setUp()
    {
        $model = new \Test\Chrome\Model\Authorisation\Adapter\Simple\Mock();
        $model->userGroup = array(1 => 1, 2 => 123456, 3 => 89123, 4 => 8388607, 5 => 168804);
        $model->resourceGroups = array(array(new \Chrome\Resource\Resource('testIsAllowed'), 'guestAllowed', 1),
                                       array(new \Chrome\Resource\Resource('testIsAllowed'), 'guestNotAllowed', 33344));

        $this->_adapter = new \Chrome\Authorisation\Adapter\Simple($model);
    }

    public function testIsAllowedProvider()
    {
        // strcuture: userid,resourcename, resource_trafo, result
        return array(
        array(1, 'testIsAllowed', 'guestAllowed', true),
        array(1, 'testIsAllowed', 'guestNotAllowed', false),
        array(2, 'testIsAllowed', 'guestAllowed', false),
        array(2, 'testIsAllowed', 'guestNotAllowed', true),
        array(3, 'testIsAllowed', 'guestAllowed', true),
        array(3, 'testIsAllowed', 'guestNotAllowed', false),
        array(4, 'testIsAllowed', 'guestAllowed', true),
        array(5, 'testIsAllowed', 'guestNotAllowed', true),
        // testing the not-existing params
        array(6, 'testIsAllowed', 'guestNotAllowed', false),
        array(4, 'testIsAllowed', 'notExisting', false),
        array(4, 'notExisting', 'guestAllowed', false),
        );
    }

    /**
     * @dataProvider testIsAllowedProvider
     */
    public function testIsAllowed($userId, $resourceName, $resourceTransformation, $expectedResult)
    {
        $resource = new \Chrome\Authorisation\Resource\Resource(new \Chrome\Resource\Resource($resourceName), $resourceTransformation);

        $this->assertEquals($expectedResult, $this->_adapter->isAllowed($resource, $userId));
    }
}

namespace Test\Chrome\Authorisation\Adapter;

class SimpleModelDefaultTest extends \Test\Chrome\TestCase
{
    protected $_model = null;

    public function setUp()
    {
        $this->_model = new \Chrome\Model\Authorisation\Adapter\Simple\Database($this->_diContainer->get('\Chrome\Database\Factory\Factory_Interface'), $this->_diContainer->get('\Chrome\Model\Database\Statement_Interface'));
        $this->_model->setResourceModel($this->_diContainer->get('\Chrome\Resource\Model_Interface'));
    }

    public function testGetAccessById()
    {
        // just random numbers, but in db
        $this->assertEquals(1234666, $this->_model->getResourceGroupByResource(new \Chrome\Resource\Resource('test'), 'read'));
        $this->assertEquals(913785, $this->_model->getResourceGroupByResource(new \Chrome\Resource\Resource('test'), 'write'));
        $this->assertEquals(18462, $this->_model->getResourceGroupByResource(new \Chrome\Resource\Resource('test2'), 'anyTrafo'));
        $this->assertEquals(0, $this->_model->getResourceGroupByResource(new \Chrome\Resource\Resource('test'), 'notExisting'));
        $this->assertEquals(0, $this->_model->getResourceGroupByResource(new \Chrome\Resource\Resource('test2'), 'notExisting'));
        $this->assertEquals(0, $this->_model->getResourceGroupByResource(new \Chrome\Resource\Resource('notExisting'), 'notExisting'));
    }

    public function testGetUserGroupById()
    {
        // just random numbers, but in db
        $this->assertEquals(1, $this->_model->getUserGroupById(1));
        $this->assertEquals(123456, $this->_model->getUserGroupById(2));
        $this->assertEquals(89123, $this->_model->getUserGroupById(3));
        $this->assertEquals(8388607, $this->_model->getUserGroupById(4));
        $this->assertEquals(168804, $this->_model->getUserGroupById(5));
        $this->assertEquals(89123, $this->_model->getUserGroupById('3'));
        // id does not exist
        $this->assertEquals(0, $this->_model->getUserGroupById(9123456));
        $this->assertEquals(0, $this->_model->getUserGroupById('127a'));
    }
}