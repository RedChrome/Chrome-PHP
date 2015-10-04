<?php
namespace Test\Chrome\DI;

use \Mockery as m;

class ViewTestClass implements \Chrome\View\View_Interface
{
    public function setVar($key, $value) {}

    public function getVar($key) {}

    public function render() {}
}

class ViewTest extends \PHPUnit_Framework_TestCase
{
    protected function _getHandler()
    {
        return new \Chrome\DI\Handler\View();
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    public function testHandler()
    {
        $handler = $this->_getHandler();
        $container = $this->_getContainer();
        $container->shouldReceive('get')->with('\Chrome\Context\View_Interface')->andReturn(m::mock('\Chrome\Context\View_Interface'));

        $class = '\Test\Chrome\DI\ViewTestClass';

        $obj = $handler->get($class, $container);

        $this->assertInstanceOf('\Chrome\View\View_Interface', $obj);

        // this should do nothing
        $handler->remove($class);

        $obj2 = $handler->get($class, $container);

        $this->assertInstanceOf('\Chrome\View\View_Interface', $obj2);
        $this->assertNotSame($obj, $obj2);

        $this->assertNull($handler->get('\Test\Chrome\DI\NotExistingViewTestClass', $container));
    }
}