<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Linker
 */
namespace Chrome\Resource;

interface Resource_Interface
{
    public function setResourceName($name);

    public function setResourceParameters(array $params);

    public function getResourceName();

    public function getResourceParameters();

    public function setResourceId($id);

    public function getResourceId();

    public function equals(Resource_Interface $resource);

    public function __toString();
}

class Resource implements Resource_Interface
{
    /**
     * @var string
     */
    protected $_resourceName = '';

    /**
     * @var string
     */
    protected $_resourceParams = array();

    /**
     * @var int
     */
    protected $_resourceId = null;

    public function __construct($resourceName, array $resourceParams = array(), $resourceId = null)
    {
        $this->setResourceName($resourceName);
        $this->setResourceParameters($resourceParams);
        $this->setResourceId($resourceId);
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->_resourceName;
    }

    /**
     * @param $resourceName
     */
    public function setResourceName($resourceName)
    {
        $this->_resourceName = $resourceName;
    }

    /**
     * @return string
     */
    public function getResourceParameters()
    {
        return $this->_resourceParams;
    }

    /**
     * @param $resourceParams
     */
    public function setResourceParameters(array $resourceParams)
    {
        $this->_resourceParams = $resourceParams;
    }

    /**
     * @see \Chrome\Resource\Resource_Interface::setResourceId()
     */
    public function setResourceId($id)
    {
        $this->_resourceId = ($id !== null) ? (int) $id : null;
    }

    /**
     * @see \Chrome\Resource\Resource_Interface::getResourceId()
     */
    public function getResourceId()
    {
        return $this->_resourceId;
    }

    public function equals(Resource_Interface $resource)
    {
        if($this->_resourceId !== null) {
            return $resource->getResourceId() === $this->_resourceId;
        } else {
            return (($resource->getResourceName() === $this->getResourceName()) AND (count(array_diff_assoc($resource->getResourceParameters(), $this->getResourceParameters()))) === 0);
        }
    }

    public function __toString()
    {
        $params = array();

        foreach($this->_resourceParams as $key => $param) {
            $params[] = $key.'='.$param;
        }

        $str = implode('/', $params);

        return $this->_resourceName.'/'.$str;
    }
}

interface Model_Interface
{
    /**
     * Returns for the resource $resource the resource id
     *
     * @param Resource_Interface $resource a resource object
     * @return int
     */
    public function getResourceId(Resource_Interface $resource);

    /**
     * Returns for the resource id a resource object
     *
     * @param int $resourceId a resource id
     * @return Resource_Interface
     */
    public function getResource($resourceId);

    public function createResource(Resource_Interface $resource);

    public function deleteResourceId($resourceId);

    public function deleteResource(Resource_Interface $resource);
}

namespace Chrome\Model\Resource;

use Chrome\Resource\Model_Interface;
use Chrome\Resource\Resource_Interface;
use Chrome\Resource\Resource;

class Database extends \Chrome_Model_Database_Statement_Abstract implements Model_Interface
{
    protected function _convertArrayParamsToString(array $params)
    {
        foreach($params as $key => $param) {
            $params[$key] = $key.'='.$param;
        }

        return implode('/', $params);
    }

    protected function _convertStringParamsToArray($params)
    {
        $keyValuePairs = explode('/', $params);

        $array = array();

        foreach($keyValuePairs as $keyValuePair)
        {
            $keyValue = explode('=', $keyValuePair);
            if(count($keyValue) === 2) {
                $array[$keyValue[0]] = $keyValue[1];
            }
        }

        return $array;
    }

    public function getResourceId(Resource_Interface $resource)
    {
        if($resource->getResourceId() !== null) {
            return $resource->getResourceId();
        }

        $db = $this->_getDBInterface();

        $this->_getDBInterface()->loadQuery('resourceGetResourceId')->execute(array($resource->getResourceName(), $this->_convertArrayParamsToString($resource->getResourceParameters())));

        $result = $db->getResult();

        if(!$result->isEmpty()) {
            $array = $result->getNext();
            $id = (int) $array['id'];
            $resource->setResourceId($id);
            return $id;
        } else {
            return 0;
        }
    }

    public function getResource($resourceId)
    {
        $resourceId = (int) $resourceId;

        $db = $this->_getDBInterface();

        $db->loadQuery('resourceGetResource')->execute(array($resourceId));

        $result = $db->getResult();

        if(!$result->isEmpty()) {
            $result = $db->getResult()->getNext();
            return new Resource($result['name'], $this->_convertStringParamsToArray($result['parameter']), $resourceId);
        } else {
            return new Resource('', array(), $resourceId);
        }
    }

    public function deleteResourceId($resourceId)
    {
        $this->_getDBInterface()->loadQuery('resourceDeleteResourceId')->execute((int) $resourceId);
    }

    public function deleteResource(Resource_Interface $resource)
    {
        $this->_getDBInterface()->loadQuery('resourceDeleteResource')->execute(array($resource->getResourceName(), $this->_convertArrayParamsToString($resource->getResourceParameters())));
    }

    public function createResource(Resource_Interface $resource)
    {
        $this->_getDBInterface()->loadQuery('resourceCreateResource')->execute(array($resource->getResourceName(), $this->_convertArrayParamsToString($resource->getResourceParameters())));
    }
}