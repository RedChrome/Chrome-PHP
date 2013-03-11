<?php

require_once 'Tests/testsetup.php';

class RouterResourceTest extends PHPUnit_Framework_TestCase
{
    public function testSetterGetter() {

        $resource = new Chrome_Router_Resource();

        $resource->setClass('Chrome_TestCase_Class');

        $resource->setFile('AnyExampleFile.php');

        $resource->setName('anyExampleName');

        $this->assertEquals('Chrome_TestCase_Class', $resource->getClass());
        $this->assertEquals('AnyExampleFile.php', $resource->getFile());
        $this->assertEquals('anyExampleName', $resource->getName());
    }
}