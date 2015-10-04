<?php
namespace Test\Chrome\DI\Loader;

use \Mockery as m;

class StructuredDirectoryTest extends \PHPUnit_Framework_TestCase
{
    protected function _getLoader()
    {
        return m::mock('\Chrome\DI\Loader\Loader_Interface');
    }

    protected function _getDirectoryIterator($dir = null)
    {
        if($dir === null) {
            $dir = $this->_getDir();
        }

        return new \Chrome\DI\Loader\StructuredDirectory($dir);
    }

    protected function _getDir()
    {
        return m::mock('\Chrome\Directory_Interface');
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    public function testLoading()
    {
        $logger = m::mock('\Psr\Log\LoggerInterface');
        $logger->shouldReceive('warning')->andReturnNull();

        $file = m::mock('\Chrome\File_Interface');
        $file->shouldReceive('requireOnce')->andReturnNull();

        $dir = $this->_getDir();
        $dir->shouldReceive('getFileIterator')->andReturn(new \ArrayIterator(array('01_class1.php', 'wrongFormat', '02_class2.php')));
        $dir->shouldReceive('file')->andReturn($file);

        $container = $this->_getContainer();

        $obj = $this->_getDirectoryIterator($dir);

        $obj->setLogger( $logger);
        $iterator = $obj->load($container);

        $this->assertTrue($iterator->count() === 2);
        $this->assertTrue(strpos($iterator->current(), '\\Chrome\\DI\\Loader\\') !== false);
    }

    public function testLoadingFailesOnWrongFileFormat()
    {
        $file = m::mock('\Chrome\File_Interface');
        $file->shouldReceive('requireOnce')->andReturnNull();

        $dir = $this->_getDir();
        $dir->shouldReceive('getFileIterator')->andReturn(new \ArrayIterator(array('file')));
        $dir->shouldReceive('file')->andReturn($file);

        $container = $this->_getContainer();
        $obj = $this->_getDirectoryIterator($dir);

        $this->assertNull($obj->getLogger());

        $this->setExpectedException('\Chrome\Exception');

        $obj->load($container);
    }

    public function testLoadingFailesAndThrowsException()
    {
        $dir = $this->_getDir();
        $dir->shouldReceive('getFileIterator')->andReturn(new \ArrayIterator(array('file')));
        $dir->shouldReceive('file')->andThrow('\Chrome\Exception');

        $container = $this->_getContainer();
        $obj = $this->_getDirectoryIterator($dir);

        $this->assertNull($obj->getLogger());

        $this->setExpectedException('\Chrome\Exception');

        $obj->load($container);
    }

    public function testExceptionIsLoggedIfLoggerAvailable()
    {
        $dir = $this->_getDir();
        $dir->shouldReceive('getFileIterator')->andReturn(new \ArrayIterator(array('file')));
        $dir->shouldReceive('file')->andThrow('\Chrome\Exception');

        $container = $this->_getContainer();
        $obj = $this->_getDirectoryIterator($dir);

        $logger = m::mock('\Psr\Log\LoggerInterface');
        $logger->shouldReceive('warning')->andReturnNull();

        $obj->setLogger($logger);

        $this->assertNotNull($obj->getLogger());

        $obj->load($container);
    }

    public function testLogger()
    {
        $obj = $this->_getDirectoryIterator();

        $logger = m::mock('\Psr\Log\LoggerInterface');

        $obj->setLogger($logger);

        $this->assertSame($logger, $obj->getLogger());
    }
}