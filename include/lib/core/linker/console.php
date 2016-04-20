<?php

namespace Chrome\Linker\Console;

use \Chrome\Linker\Linker_Interface;
use \Chrome\Resource\Resource_Interface;
use \Chrome\Resource\Model_Interface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Linker
 */
class Linker implements Linker_Interface
{
    protected $_model = null;

    protected $_resourceHelper = array();

    protected $_resourceIdHelper = array();

    public function getBasepath()
    {
        //TODO
    }

    public function __construct(Model_Interface $model)
    {
        $this->_model = $model;
    }

    protected function _noLinkFound($resource, $relative)
    {
        return $this->_absolutePrefix.'404?'.$resource;
    }


    public function getLink($resourceId)
    {
        if(!is_int($resourceId)) {
            throw new \Chrome\Exception('$resourceId must be of type integer, given '.$resourceId);
        }

        // TODO: finish

        return $this->_noLinkFound($resource, $relative);
    }

    public function get(Resource_Interface $resource)
    {
        // TODO: finish
        return $resource->getName();

        if($resource->getId() !== null) {
            return $this->getLink($resource->getId(), $relative);
        }

        return $this->_noLinkFound($resource, $relative);
    }
}