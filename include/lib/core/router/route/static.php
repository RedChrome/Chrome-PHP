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
class StaticRoute extends AbstractRoute
{
    public function match(\Chrome\URI\URI_Interface $url, \Chrome\Request\Data_Interface $data)
    {
        $path = trim($url->getPath());

        /*
         * This enables/disabled, that urls may end with .html but still get mapped to the same resource
         */

        /*
            $pos = strripos($path, '.html');

            if($pos !== false) {
                $path = substr($path, 0, $pos);
            }
        */

        $row = $this->_model->getRoute($path);

        if($row == false)
        {
            return false;
        }

        $this->_result = new \Chrome\Router\Result();
        $this->_result->setClass($row['class']);

        if(count($row['GET']) > 0)
        {
            $data->setGETData($row['GET']);
        }

        if(count($row['POST']) > 0)
        {
            $data->setPOSTData($row['POST']);
        }

        return true;
    }
}

namespace Chrome\Model\Route\StaticRoute;

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
 * load \Chrome\Linker\HTTP\Helper\Model\Static_Interface interface
 */
require_once LIB.'core/linker/http/staticInterface.php';

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Database extends \Chrome\Model\AbstractDatabaseStatement implements \Chrome\Linker\HTTP\Model\Static_Interface
{
    protected $_resourceModel = null;

    public function setResourceModel(\Chrome\Resource\Model_Interface $resourceModel)
    {
        $this->_resourceModel = $resourceModel;
    }

    public function getRoute($search)
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('routeStaticGetRoute')->execute(array($search));

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

        return $row;
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
