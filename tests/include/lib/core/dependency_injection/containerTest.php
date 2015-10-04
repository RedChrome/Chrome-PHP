<?php
namespace Test\Chrome\DI;

use \Mockery as m;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    protected function _getContainer()
    {
        return new \Chrome\DI\Container();
    }

    protected function _getHandler()
    {
        return m::mock('\Chrome\DI\Handler_Interface');
    }

    public function testHandlers()
    {
        $faker = \Faker\Factory::create();

        $container = $this->_getContainer();
        $handler = $this->_getHandler();

        $handlerName = $faker->name;

        $this->assertFalse($container->isAttached($handlerName));

        $container->attachHandler($handlerName, $handler);

        $this->assertTrue($container->isAttached($handlerName));
        $this->assertSame($handler, $container->getHandler($handlerName));

        $container->detachHandler($handlerName);
        $this->assertFalse($container->isAttached($handlerName));

        $container->detachHandler($handlerName);
        $this->assertFalse($container->isAttached($handlerName));
    }

    public function testAttachHandlerWithNoString()
    {
        $container = $this->_getContainer();
        $handler = $this->_getHandler();

        $this->setExpectedException('\Chrome\InvalidArgumentException');

        $container->attachHandler(function() {return 'handler';}, $handler);
    }

    public function testGetNotExistingHandler()
    {
        $container = $this->_getContainer();
        $handler = $this->_getHandler();

        $container->attachHandler('handler', $handler);

        $this->setExpectedException('\Chrome\InvalidArgumentException');
        $container->getHandler('handler2');
    }

    public function testDI()
    {
        $faker = \Faker\Factory::create();

        $container = $this->_getContainer();
        $handler   = $this->_getHandler();
        $handler2  = $this->_getHandler();
        $handler3  = $this->_getHandler();

        $container->attachHandler('handler', $handler);
        $container->attachHandler('handler2', $handler2);
        $container->attachHandler('handler3', $handler2);

        $getName = $faker->name;
        $handler->shouldReceive('get', array($getName, $container))->andReturnNull();
        $handler2->shouldReceive('get', array($getName, $container))->andReturn($handler2);
        $handler3->shouldNotReceive('get', array($getName, $container));
        $handler->shouldReceive('remove', array($getName));
        $handler2->shouldReceive('remove', array($getName));
        $handler3->shouldReceive('remove', array($getName));

        $this->assertSame($handler2, $container->get($getName));

        $container->remove($getName);
    }

    public function testDIOnException()
    {
        $faker     = \Faker\Factory::create();
        $container = $this->_getContainer();
        $handler   = $this->_getHandler();
        $handler->shouldReceive('get')->andReturnNull();
        $container->attachHandler('handler', $handler);

        $this->setExpectedException('\Chrome\Exception');

        $container->get($faker->name);
    }

    public function testContainerHandlesExceptionFromHandler()
    {
        $container = $this->_getContainer();
        $handler   = $this->_getHandler();
        $handler->shouldReceive('get')->andThrow('\Chrome\InvalidArgumentException');
        $container->attachHandler('handler', $handler);

        $this->setExpectedException('\Chrome\Exception');

        $container->get('key');
    }
}