<?php
namespace Test\Chrome\DI;

use \Mockery as m;

class ControllerTestClass extends \Chrome\Controller\AbstractController {
    protected function _initialize() {}
    protected function _execute() {}
    protected function _shutdown() {}
    public function execute() {}
}

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    protected function _getHandler()
    {
        return new \Chrome\DI\Handler\Controller();
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    protected function _getExceptionHandler()
    {
        return m::mock('\Chrome\Exception\Handler_Interface');
    }

    protected function _getRequestContext()
    {
        $mock = m::mock('\Chrome\Request\RequestContext_Interface');
        $mock->shouldReceive('getRequest')->andReturnNull();

        return $mock;
    }

    protected function _getAppContext()
    {
        $mock = m::mock('\Chrome\Context\Application_Interface');
        $mock->shouldReceive('getRequestContext')->andReturn($this->_getRequestContext());

        return $mock;
    }

    public function testGet()
    {
        $handler = $this->_getHandler();

        $container = $this->_getContainer();
        $container->shouldReceive('get')->with('\Chrome\Exception\Handler_Interface')->andReturn($this->_getExceptionHandler());
        $container->shouldReceive('get')->with('\Chrome\Context\Application_Interface')->andReturn($this->_getAppContext());

        // this should do nothing!
        $handler->remove('\Test\Chrome\DI\ControllerTestClass');

        $obj = $handler->get('\Test\Chrome\DI\ControllerTestClass', $container);
        $this->assertInstanceOf('\Chrome\Controller\Controller_Interface', $obj);
        // this will now be injected by the invoker, same goes for the application context.
        //$this->assertInstanceOf('\Chrome\Exception\Handler_Interface', $obj->getExceptionHandler());

        $this->assertNull($handler->get('\Test\Chrome\DI\NotExistingControllerTestClass', $container));
    }
}