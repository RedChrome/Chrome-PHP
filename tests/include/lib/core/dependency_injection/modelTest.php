<?php
namespace Test\Chrome\DI;

use \Mockery as m;

class DatabaseStatementClass extends \Chrome\Model\AbstractDatabaseStatement
{
}

class ModelTest extends \PHPUnit_Framework_TestCase
{
    protected function _getHandler()
    {
        return new \Chrome\DI\Handler\Model();
    }

    protected function _getContainer()
    {
        return m::mock('\Chrome\DI\Container_Interface');
    }

    protected function _getDBFactory()
    {
        return m::mock('\Chrome\Database\Factory\Factory_Interface');
    }

    protected function _getDBStatement()
    {
        return m::mock('\Chrome\Model\Database\Statement_Interface');
    }

    protected function _getRequestContext()
    {
        $mock = m::mock('\Chrome\Request\RequestContext_Interface');
        $mock->shouldReceive('getRequest')->andReturnNull();

        return $mock;
    }

    protected function _getAppContext()
    {
        $mock = m::mock('\Chrome\Context\Application_Interface');
        $mock->shouldReceive('getRequestContext')->andReturn($this->_getRequestContext());

        return $mock;
    }

    public function testGet()
    {
        $container = $this->_getContainer();
        $handler = $this->_getHandler();

        $container->shouldReceive('get')->with('\Chrome\Database\Factory\Factory_Interface')->andReturn($this->_getDBFactory());
        $container->shouldReceive('get')->with('\Chrome\Model\Database\Statement_Interface')->andReturn($this->_getDBStatement());
        $container->shouldReceive('get')->with('\Chrome\Logger\Model')->andReturn(m::mock('\Psr\Log\LoggerInterface'));

        // this should do nothing
        $handler->remove('\Test\Chrome\DI\DatabaseStatementClass');

        $obj = $handler->get('\Test\Chrome\DI\DatabaseStatementClass', $container);
        $this->assertInstanceOf('\Chrome\Model\AbstractDatabaseStatement', $obj);

        $this->assertNull($handler->get('\Test\Chrome\DI\NotExistingDatabaseStatementClass', $container));
    }
}