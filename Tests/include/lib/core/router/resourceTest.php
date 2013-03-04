<?php

require_once 'Tests/testsetup.php';

class RouterResourceTest extends PHPUnit_Framework_TestCase
{
    public function testSetterGetter() {

        $resource = new Chrome_Router_Resource();

        $resource->setClass('Chrome_Test_Class');

        $resource->setFile('AnyExampleFile.php');

        $resource->setName('anyExampleName');


        $this->assertEquals('Chrome_Test_Class', $resource->getClass());
        $this->assertEquals('AnyExampleFile.php', $resource->getFile());
        $this->assertEquals('anyExampleName', $resource->getName());
    }

    public function testInitClassWithNoClass() {
        $resource = new Chrome_Router_Resource();

        // no class set, so an exception should be thrown
        $this->setExpectedException('Chrome_Exception');

        $resource->initClass(Chrome_Front_Controller::getInstance()->getRequestHandler());
    }

    public function testInitClassWithNotExistingClass() {

        $resource = new Chrome_Router_Resource();

        $resource->setClass('AnyNotExistingClass');

        // no class set, so an exception should be thrown
        $this->setExpectedException('Chrome_Exception');

        $resource->initClass(Chrome_Front_Controller::getInstance()->getRequestHandler());
    }
}