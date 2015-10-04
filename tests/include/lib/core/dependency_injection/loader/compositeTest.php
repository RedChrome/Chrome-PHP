<?php
namespace Test\Chrome\DI\Loader;

use \Mockery as m;

class CompositeTest extends \PHPUnit_Framework_TestCase
{
    protected function _getLoader()
    {
        return m::mock('\Chrome\DI\Loader\Loader_Interface');
    }

    public function testAdd()
    {
        $loader1 = $this->_getLoader();
        $loader2 = $this->_getLoader();

        $container = m::mock('\Chrome\DI\Container_Interface');

        $composite = new \Chrome\DI\Loader\Composite();

        $composite->add($loader1);
        $composite->add($loader2);

        $loader1->shouldReceive('load')->once()->with($container)->andReturnNull();
        $loader2->shouldReceive('load')->once()->with($container)->andReturnNull();

        $composite->load($container);
    }
}