<?php

namespace Test\Chrome\Router;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterGetter()
    {
        $resource = new \Chrome\Router\Result();

        $resource->setClass('\Test\Chrome\TestCase_Class');

        $this->assertEquals('\Test\Chrome\TestCase_Class', $resource->getClass());
    }
}