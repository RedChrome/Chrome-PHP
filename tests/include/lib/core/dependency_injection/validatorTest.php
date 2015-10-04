<?php
namespace Test\Chrome\DI;

use \Mockery as m;

class ValidatorTestClass extends \Chrome\Validator\Configurable\AbstractConfigurable
{
    protected function _validate() {}
}

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected function _getHandler()
    {
        return new \Chrome\DI\Handler\Validator();
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    public function testHandler()
    {
        $handler = $this->_getHandler();
        $container = $this->_getContainer();
        $container->shouldReceive('get')->with('\Chrome\Config\Config_Interface')->andReturn(m::mock('\Chrome\Config\Config_Interface'));

        $class = '\Test\Chrome\DI\ValidatorTestClass';

        $obj = $handler->get($class, $container);

        $this->assertInstanceOf('\Chrome\Validator\Configurable\AbstractConfigurable', $obj);

        // this should do nothing
        $handler->remove($class);

        $obj2 = $handler->get($class, $container);

        $this->assertInstanceOf('\Chrome\Validator\Configurable\AbstractConfigurable', $obj2);
        $this->assertNotSame($obj, $obj2);

        $this->assertNull($handler->get('\Test\Chrome\DI\NotExistingValidatorTestClass', $container));
    }
}