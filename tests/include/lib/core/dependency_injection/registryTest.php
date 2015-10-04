<?php
namespace Test\Chrome\DI;

use \Mockery as m;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    protected function _getHandler()
    {
        return new \Chrome\DI\Handler\Registry();
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    protected function _getObject()
    {
        $faker = \Faker\Factory::create();
        $available = array_merge(get_declared_interfaces());

        return m::mock($faker->randomElement($available));
    }

    public function testFunctionality()
    {
        $faker = \Faker\Factory::create();
        $handler = $this->_getHandler();
        $container = $this->_getContainer();

        $name = $faker->name;

        $obj = $this->_getObject();

        $this->assertFalse($handler->has($name));
        $handler->add($name, $obj);
        $this->assertTrue($handler->has($name));
        $this->assertSame($obj, $handler->get($name, $container));

        $handler->remove($name);
        $this->assertFalse($handler->has($name));
    }

    public function testAddingNonObject()
    {
        $handler = $this->_getHandler();

        $obj = '';

        $this->setExpectedException('\Chrome\Exception');

        $handler->add('key', $obj);
    }

    public function testUsingNotAStringAsName()
    {
        $handler = $this->_getHandler();

        $obj = $this->_getObject();

        $this->setExpectedException('\Chrome\Exception');

        $handler->add(function() {}, $obj);
    }
}