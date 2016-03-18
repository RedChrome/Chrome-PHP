<?php

namespace Test\Chrome\Router;

use \Mockery as M;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterGetter()
    {
        $resource = new \Chrome\Router\Result();

        $resource->setClass('\Test\Chrome\TestCase_Class');

        $this->assertEquals('\Test\Chrome\TestCase_Class', $resource->getClass());

        $request = M::mock('\Psr\Http\Message\ServerRequestInterface');

        $resource->setRequest($request);

        $this->assertSame($request, $resource->getRequest());
    }
}