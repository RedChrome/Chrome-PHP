<?php

namespace Test\Chrome\Model\Resource;

use \Chrome\Resource\Resource_Interface;

class Resource implements \Chrome\Resource\Model_Interface
{
    public function getResourceId(Resource_Interface $resource)
    {
        return $resource->getResourceId();
    }

    public function getResource($resourceId)
    {
        // do nothing
    }

    public function deleteResourceId($resourceId)
    {
        // do nothing
    }

    public function deleteResource(Resource_Interface $resource)
    {
        // do nothing
    }

    public function createResource(Resource_Interface $resource)
    {
        // do nothing
    }
}
