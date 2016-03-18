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

/**
 * Forbidden input chars are: | ~ and =
 *
 *
 */
interface Resource_Interface
{
    public function getParameters();

    public function getId();

    public function equals(Resource_Interface $resource);

    public function toString();

    public function __toString();

    /**
     *
     * @param string $string
     * @return void
     * @throws \Chrome\Exception
     */
    public function fromString($string);
}

abstract class AbstractResource implements Resource_Interface
{
    /**
     * @var array
     */
    protected $_resourceParams = array();

    /**
     * @var string
     */
    protected $_resourceId = null;

    public function getParameters()
    {
        return $this->_resourceParams;
    }

    public function getId()
    {
        return $this->_resourceId;
    }

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * If the string could not get parsed, an exception is thrown
     *
     * @param unknown $keyValueString
     */
    protected function _parseKeyValuePairString($keyValueString)
    {
        $keyAndValue = explode('=', $keyValueString);
        if(count($keyAndValue) === 2) {
            return array($keyAndValue[0] => $keyAndValue[1]);
        }

        throw new \Chrome\Exception('Could not parse key-value-string.');
    }
}

class Resource extends AbstractResource
{
    protected $_prefix = 'res:';

    public function __construct($id)
    {
        $this->_resourceId = $id;
    }

    public function setParameters(array $resourceParams)
    {
        $this->_resourceParams = $resourceParams;
        return $this;
    }

    public function setId($id)
    {
        $this->_resourceId = ($id !== null) ? $id : null;
        return $this;
    }

    public function toString()
    {
        $params = array();

        foreach($this->_resourceParams as $key => $param) {
            $params[] = $key.'='.$param;
        }

        $str = implode('|', $params);

        return $this->_prefix.$this->_resourceId.'|'.$str;
    }

    public function fromString($string)
    {
        if(!preg_match('#^'.$this->_prefix.'(.*)#', $string, $matches) === 1) {
            if(($pos = strpos($matches[1], '|') ) !== false) {
                $id = substr($matches[1], 0, $pos);
                $paramstring = substr($matches[1], $pos);
                $params = explode('|', $paramstring);

                foreach($params as $parameter)
                {
                    $keyValue = $this->_parseKeyValuePairString($parameter);
                    $this->_resourceParams[key($keyValue)] = current($keyValue);
                }

                $this->_resourceId = $id;
            }
        }
    }

    public function equals(Resource_Interface $resource)
    {
        return $this->_resourceId === $resource->getId() AND count(array_diff_assoc($resource->getParameters(), $this->getParameters())) === 0;
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
    public function getId(Resource_Interface $resource);

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

class Database extends \Chrome\Model\AbstractDatabaseStatement implements Model_Interface
{
    public function getId(Resource_Interface $resource)
    {
        $db = $this->_getDBInterface();

        $db->loadQuery('resourceGetResourceId')->execute(array($resource->toString()));

        $result = $db->getResult();

        if(!$result->isEmpty()) {
            $row = $result->getNext();
            return (int) $row['id'];
        }

        return 0;
    }

    public function getResource($resourceId)
    {
        // FIXME: what to do?
        throw new \Chrome\Exception('Not finished');

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
        $this->_getDBInterface()->loadQuery('resourceDeleteResource')->execute(array($resource->getName()));
    }

    public function createResource(Resource_Interface $resource)
    {
        $this->_getDBInterface()->loadQuery('resourceCreateResource')->execute(array($resource->getName()));
    }
}