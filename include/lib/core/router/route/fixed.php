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
 * @subpackage Chrome.Router
 */

namespace Chrome\Router\Route;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class FixedRoute extends AbstractRoute
{
    public function match(\Psr\Http\Message\ServerRequestInterface $request, $normalizedPath)
    {
        /*
         * This enables/disabled, that urls may end with .html but still get mapped to the same resource
         */

        /*
            $pos = strripos($path, '.html');

            if($pos !== false) {
                $path = substr($path, 0, $pos);
            }
        */

        $row = $this->_model->findRouteByName($normalizedPath);

        if($row == false)
        {
            return false;
        }

        $this->_result = new \Chrome\Router\Result();
        $this->_result->setClass($row['class']);
        $this->_result->setRequest($this->_applyGetAndPost($request, $row['GET'], $row['POST']));

        return true;
    }
}

namespace Chrome\Model\Route\FixedRoute;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Cache extends \Chrome\Model\AbstractCache
{
    const CACHE_NAMESPACE_GET_ROUTE = 1;
    const CACHE_NAMESPACE_FINDE_ROUTE = 2;

    public function getRoute($search)
    {
        if(($return = $this->_cache->get(self::CACHE_NAMESPACE_GET_ROUTE . $search)) === null)
        {
            $return = $this->_decorable->getRoute($search);

            if($return !== false)
            {
                $this->_cache->set(self::CACHE_NAMESPACE_GET_ROUTE. $search, $return);
            }
        }

        return $return;
    }

    public function findRoute($search)
    {
        if(($return = $this->_cache->get(self::CACHE_NAMESPACE_FINDE_ROUTE. $search)) === null)
        {
            $return = $this->_decorable->findRoute($search);

            if($return !== false)
            {
                $this->_cache->set(self::CACHE_NAMESPACE_FINDE_ROUTE. $search, $return);
            }
        }

        return $return;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Database extends \Chrome\Model\AbstractDatabaseStatement
{
    protected $_resourceModel = null;

    public function setResourceModel(\Chrome\Resource\Model_Interface $resourceModel)
    {
        $this->_resourceModel = $resourceModel;
    }

    public function findRouteByName($search)
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('routeFixedGetRoute')->execute(array($search));

        $row = $result->getNext();

        if($row === false)
        {
            return false;
        }

        // translate key=value,key2=value2 into an array {key => value, key2=>value2}
        $get = array();
        if(!empty($row['GET']))
        {
            // input is like key=value,key2=value2,..
            $keyValuePairs = explode(',', $row['GET']);
            foreach($keyValuePairs as $keyValuePair)
            {
                $keyValue = explode('=', $keyValuePair);
                $get[$keyValue[0]] = $keyValue[1];
            }
        }

        $row['GET'] = $get;

        if(count($get) == 0) {
            $row['GET'] = null;
        }


        $post = array();
        if(!empty($row['POST']))
        {
            // input is like key=value,key2=value2,..
            $keyValuePairs = explode(',', $row['POST']);
            foreach($keyValuePairs as $keyValuePair)
            {

                $keyValue = explode('=', $keyValuePair);
                $post[$keyValue[0]] = $keyValue[1];
            }
        }

        $row['POST'] = $post;

        if(count($post) == 0) {
            $row['POST'] = null;
        }

        return $row;
    }

    public function findLinkByIdentifier($identifier)
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('routeFixedFindResourceByIdentifier')->execute(array($identifier));

        if(!$result->isEmpty()) {
            $row = $result->getNext();

            return $row['link'];
        }

        return false;
    }

    public function findLinkByName($name)
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('routeFixedFindResource')->execute(array($name));

        if(!$result->isEmpty()) {
            $row = $result->getNext();

            return $row['link'];
        }

        return false;
    }

    public function findRoute($name)
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('routeStaticFindRoute')->execute(array($name));

        $row = $result->getNext();

        return $row;
    }

    public function getLinkByResource(\Chrome\Resource\Resource_Interface $resource)
    {
        $resourceId = $this->_resourceModel->getId($resource);

        $db = $this->_getDBInterface();

        $result = $db->loadQuery('routeStaticFindResource')->execute(array($resourceId));

        if(!$result->isEmpty()) {
            $row = $result->getNext();

            return $row['link'];
        }

        return false;
    }
}
