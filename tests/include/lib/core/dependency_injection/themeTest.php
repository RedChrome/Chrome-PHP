<?php
namespace Test\Chrome\DI;

use \Mockery as m;

class ThemeTestClass implements \Chrome\Design\Theme_Interface
{
    public function setApplicationContext(\Chrome\Context\Application_Interface $appContext){}

    public function setDesign(\Chrome\Design\Design_Interface $design) {}

    public function setController(\Chrome\Controller\Controller_Interface $controller){}

    public function apply() {}
}

class ThemeTest extends \PHPUnit_Framework_TestCase
{
    protected function _getHandler()
    {
        return new \Chrome\DI\Handler\Theme();
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    public function testHandler()
    {
        $handler = $this->_getHandler();
        $container = $this->_getContainer();
        $container->shouldReceive('get')->with('\Chrome\Context\Application_Interface')->andReturn(m::mock('\Chrome\Context\Application_Interface'));

        $class = '\Test\Chrome\DI\ThemeTestClass';

        $obj = $handler->get($class, $container);

        $this->assertInstanceOf('\Chrome\Design\Theme_Interface', $obj);

        // this should do nothing
        $handler->remove($class);

        $obj2 = $handler->get($class, $container);

        $this->assertInstanceOf('\Chrome\Design\Theme_Interface', $obj2);
        $this->assertNotSame($obj, $obj2);

        $this->assertNull($handler->get('\Test\Chrome\DI\NotExistingThemeTestClass', $container));
    }
}