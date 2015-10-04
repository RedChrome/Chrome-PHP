<?php
namespace Test\Chrome\DI\Loader;

use \Mockery as m;

class DummyClass {
    public function load($diContainer) {
        $diContainer->increment();
    }
}
class DummyClass2 extends DummyClass {}
class DummyClass3 extends DummyClass {}

class ClassIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected function _getLoader()
    {
        return m::mock('\Chrome\DI\Loader\Loader_Interface');
    }

    protected function _getClassIterator($iterator = null)
    {
        if($iterator === null) {
            $iterator = new \ArrayIterator(array());
        }

        return new \Chrome\DI\Loader\ClassIterator($iterator);
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    public function testLoad()
    {
        $iterator = new \ArrayIterator(array('\Test\Chrome\DI\Loader\DummyClass', '\Test\Chrome\DI\Loader\DummyClass2', '\Test\Chrome\DI\Loader\DummyClass3'));
        $container = $this->_getContainer();
        $container->shouldReceive('increment')->times(3)->andReturnNull();
        $obj = $this->_getClassIterator($iterator);

        $obj->load($container);
    }

    public function testLoadingFailesOnWrongType()
    {
        $iterator = new \ArrayIterator(array(function() {return 'class';}));
        $container = $this->_getContainer();
        $obj = $this->_getClassIterator($iterator);

        $this->assertNull($obj->getLogger());

        $this->setExpectedException('\Chrome\Exception');

        $obj->load($container);
    }

    public function testLoadingFailesOnNotExistingClasses()
    {
        $iterator = new \ArrayIterator(array('\Test\Chrome\DI\Loader\NotExistingClass'));
        $container = $this->_getContainer();
        $obj = $this->_getClassIterator($iterator);

        $this->assertNull($obj->getLogger());

        $this->setExpectedException('\Chrome\Exception');

        $obj->load($container);
    }

    public function testExceptionIsLoggedIfLoggerAvailable()
    {
        $iterator = new \ArrayIterator(array('\Test\Chrome\DI\Loader\NotExistingClass'));
        $container = $this->_getContainer();
        $obj = $this->_getClassIterator($iterator);

        $logger = m::mock('\Psr\Log\LoggerInterface');
        $logger->shouldReceive('warning')->andReturnNull();

        $obj->setLogger($logger);

        $this->assertNotNull($obj->getLogger());

        $obj->load($container);
    }

    public function testLogger()
    {
        $obj = $this->_getClassIterator();

        $logger = m::mock('\Psr\Log\LoggerInterface');

        $obj->setLogger($logger);

        $this->assertSame($logger, $obj->getLogger());
    }
}