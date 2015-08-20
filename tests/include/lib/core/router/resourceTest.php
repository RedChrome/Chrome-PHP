<?php

namespace Test\Chrome\Router;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterGetter()
    {
        $resource = new \Chrome\Router\Result();

        $resource->setClass('\Test\Chrome\TestCase_Class');
        $resource->setFile('AnyExampleFile.php');
        $resource->setName('anyExampleName');

        $this->assertEquals('\Test\Chrome\TestCase_Class', $resource->getClass());
        $this->assertEquals('AnyExampleFile.php', $resource->getFile());
        $this->assertEquals('anyExampleName', $resource->getName());
    }
}