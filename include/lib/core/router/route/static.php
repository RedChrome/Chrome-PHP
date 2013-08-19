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
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.04.2013 14:53:11] --> $
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Route_Static implements Chrome_Router_Route_Interface
{

    protected $_resource = null;

    protected $_model = null;

    public function __construct(Chrome_Model_Abstract $model)
    {
        $this->_model = $model;
    }

    public function match(Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data)
    {
        $path = trim($url->getPath());

        $pos = strripos($path, '.html');

        if($pos !== false)
        {
            $path = substr($path, 0, $pos);
        }

        $row = $this->_model->getRoute($path);

        if($row == false)
        {
            return false;
        }

        $this->_resource = new Chrome_Router_Resource();
        $this->_resource->setFile($row['file']);
        $this->_resource->setClass($row['class']);

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

    public function getResource()
    {
        return $this->_resource;
    }

    public function url(Chrome_Router_Resource_Interface $resource)
    {
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Model_Route_Static_Cache extends Chrome_Model_Cache_Abstract
{
    const CHROME_MODEL_ROUTE_STATIC_CACHE_CACHE_FILE = 'tmp/cache/router/_static.cache';

    protected function _setUpCache()
    {
        $this->_cacheOption = new Chrome_Cache_Option_Serialization();
        $this->_cacheOption->setCacheFile(self::CHROME_MODEL_ROUTE_STATIC_CACHE_CACHE_FILE);

        $this->_cacheInterface = 'serialization';
    }

    public function getRoute($search)
    {
        if(($return = $this->_cache->get('getRoute_' . $search)) === null)
        {

            $return = $this->_decorable->getRoute($search);

            if($return !== false)
            {
                $this->_cache->set('getRoute_' . $search, $return);
            }
        }

        return $return;
    }

    public function findRoute($search)
    {
        if(($return = $this->_cache->get('findRoute_' . $search)) === null)
        {

            $return = $this->_decorable->findRoute($search);

            if($return !== false)
            {
                $this->_cache->set('findRoute_' . $search, $return);
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
class Chrome_Model_Route_Static_DB extends Chrome_Model_Database_Abstract
{

    protected function _setDatabaseOptions()
    {
        $this->_dbInterface = 'model';
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
        $GET = array();
        if(!empty($row['GET']))
        {

            // input is like key=value,key2=value2,..
            $keyValuePairs = explode(',', $row['GET']);
            foreach($keyValuePairs as $keyValuePair)
            {

                $keyValue = explode('=', $keyValuePair);
                $GET[$keyValue[0]] = $keyValue[1];
            }
        }
        $row['GET'] = $GET;

        $POST = array();
        if(!empty($row['POST']))
        {

            // input is like key=value,key2=value2,..
            $keyValuePairs = explode(',', $row['POST']);
            foreach($keyValuePairs as $keyValuePair)
            {

                $keyValue = explode('=', $keyValuePair);
                $POST[$keyValue[0]] = $keyValue[1];
            }
        }
        $row['POST'] = $POST;

        return $row;
    }

    public function findRoute($name)
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('routeStaticFindRoute')->execute(array($name));

        $row = $result->getNext();

        return $row;
    }
}
