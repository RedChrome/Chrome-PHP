<?php

namespace Test\Chrome\Model\Authorisation\Adapter\Simple;

require_once LIB.'core/authorisation/adapter/simple.php';

use \Chrome\Resource\Resource_Interface;

class Mock implements \Chrome\Model\Authorisation\Adapter\Simple\Model_Interface
{
    /**
     * the key (userid) maps to user group id
     *
     * @var array
     */
    public $userGroup = array();

    /**
     * Contains array of the structure
     *
     * (Resource_Interface $resource, $transformation, $resourceGroup)
     *
     * @var array
     */
    public $resourceGroups = array();

    public function getResourceGroupByResource(Resource_Interface $resource, $transformation)
    {
        foreach($this->resourceGroups as $array)
        {
            if($array[1] === $transformation AND $resource->equals($array[0])) {
                return $array[2];
            }
        }

        return 0;
    }

    public function getUserGroupById($id)
    {
        return (isset($this->userGroup[$id])) ? $this->userGroup[$id] : 0;
    }
}