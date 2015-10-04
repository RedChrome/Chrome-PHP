<?php
namespace Test\Chrome\DI;

use \Mockery as m;

class ClosureTest extends \PHPUnit_Framework_TestCase
{
    protected function _getHandler()
    {
        return new \Chrome\DI\Handler\Closure();
    }

    public function _getClosure()
    {
        return function() {};
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    public function testAddingAndRemoving()
    {
        $closure = $this->_getClosure();
        $handler = $this->_getHandler();
        $staticClosure = $this->_getClosure();

        $faker = \Faker\Factory::create();
        $closureName = $faker->name;
        $closureStaticName = $faker->name;

        $this->assertNotSame($closureName, $closureStaticName);

        $this->assertFalse($handler->has($closureName));
        $this->assertFalse($handler->has($closureStaticName));

        $handler->add($closureName, $closure);
        $handler->add($closureStaticName, $staticClosure, true);

        $this->assertTrue($handler->has($closureName));
        $this->assertTrue($handler->has($closureStaticName));

        $this->assertFalse($handler->isStatic($closureName));
        $this->assertTrue($handler->isStatic($closureStaticName));

        $handler->remove($closureName);
        $handler->remove($closureStaticName);

        $this->assertFalse($handler->has($closureName));
        $this->assertFalse($handler->has($closureStaticName));
    }

    public function testUsingClosure()
    {
        $container = $this->_getContainer();
        $container->shouldReceive('test')->twice();

        $handler = $this->_getHandler();

        $thisObj = $this;

        $closure = function($c) use ($thisObj) {
            $c->test();
            return $thisObj->_getClosure();
        };

        $handler->add('key', $closure);

        $call1 = $handler->get('key', $container);
        $call2 = $handler->get('key', $container);

        $this->assertInstanceOf('\Closure', $call1);
        $this->assertInstanceOf('\Closure', $call2);

        $this->assertNotSame($call1, $call2);
    }


    public function testUsingClosureStatic()
    {
        $container = $this->_getContainer();
        $container->shouldReceive('test')->once();
        $handler = $this->_getHandler();

        $thisObj = $this;

        $closure = function($c) use ($thisObj) {
            $c->test();
            return $thisObj->_getClosure();
        };

        $handler->add('key', $closure, true);

        $call1 = $handler->get('key', $container);
        $call2 = $handler->get('key', $container);

        $this->assertInstanceOf('\Closure', $call1);
        $this->assertInstanceOf('\Closure', $call2);

        $this->assertSame($call1, $call2);
    }

    public function testWithInvalidClosure()
    {
        $container = $this->_getContainer();
        $handler = $this->_getHandler();

        $thisObj = $this;

        $closure = function($c) {
            return null;
        };

        $handler->add('key', $closure);

        $this->setExpectedException('\Chrome\Exception');

        $handler->get('key', $container);
    }


}