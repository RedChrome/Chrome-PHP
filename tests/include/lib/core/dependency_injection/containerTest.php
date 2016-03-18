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

    protected function _getInvoker()
    {
        return m::mock('\Chrome\DI\Invoker_Interface');
    }

    public function testHandlers()
    {
        $faker = \Faker\Factory::create();

        $container = $this->_getContainer();
        $handler = $this->_getHandler();

        $handlerName = $faker->name;

        $this->assertFalse($container->isAttachedHandler($handlerName));

        $container->attachHandler($handlerName, $handler);

        $this->assertTrue($container->isAttachedHandler($handlerName));
        $this->assertSame($handler, $container->getHandler($handlerName));

        $container->detachHandler($handlerName);
        $this->assertFalse($container->isAttachedHandler($handlerName));

        $container->detachHandler($handlerName);
        $this->assertFalse($container->isAttachedHandler($handlerName));
    }

    public function testInvokers()
    {
        $faker = \Faker\Factory::create();

        $container = $this->_getContainer();
        $invoker = $this->_getInvoker();

        $invokerName = $faker->name;

        $this->assertFalse($container->isAttachedInvoker($invokerName));

        $container->attachInvoker($invokerName, $invoker);

        $this->assertTrue($container->isAttachedInvoker($invokerName));
        $this->assertSame($invoker, $container->getInvoker($invokerName));

        $container->detachInvoker($invokerName);
        $this->assertFalse($container->isAttachedInvoker($invokerName));

        // is still okay.
        $container->detachInvoker($invokerName);
    }

    public function testInvokerNotDefinedThrowsException()
    {
        $faker = \Faker\Factory::create();

        $container = $this->_getContainer();
        $name = $faker->name;
        $this->assertFalse($container->isAttachedInvoker($name));

        $this->setExpectedException('\Chrome\Exception');
        $container->getInvoker($name);
    }

    public function testInvokersAreActuallyInvoked()
    {
        $object = new \Exception();

        $faker = \Faker\Factory::create();

        $container = $this->_getContainer();
        $handler = $this->_getHandler();

        $invoker[] = $this->_getInvoker();
        $invoker[] = $this->_getInvoker();
        $invoker[] = $this->_getInvoker();

        $container->attachHandler($faker->name, $handler);

        $handler->shouldReceive('get')->andReturn($object);


        foreach($invoker as $key => $inv) {
            $inv->shouldReceive('invoke')->withArgs(array($object, $container))->andReturnNull();
            $container->attachInvoker((string) $key, $inv);
        }

        $container->get($faker->name);
    }

    public function testAttachInvokerWithNoString()
    {
        $container = $this->_getContainer();
        $invoker = $this->_getInvoker();

        $this->setExpectedException('\Chrome\InvalidArgumentException');

        $container->attachInvoker(function() {}, $invoker);

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

        $this->setExpectedException('Chrome\DI\Exception\ContainerException');
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

        $this->setExpectedException('\Chrome\DI\Exception\NotFoundException');

        $container->get($faker->name);
    }

    public function testContainerHandlesExceptionFromHandler()
    {
        $container = $this->_getContainer();
        $handler   = $this->_getHandler();
        $handler->shouldReceive('get')->andThrow('\Chrome\InvalidArgumentException');
        $container->attachHandler('handler', $handler);

        $this->setExpectedException('\Chrome\DI\Exception\ContainerException');

        $container->get('key');
    }
}