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
 * @package    CHROME-PHP
 * @subpackage Chrome.Router
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [26.11.2012 10:07:45] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();
//TODO: Add a db scheme for Chrome_Model_Route_Dynamic_DB::getResourcesAsArray
//TODO: Add regex support for single path, e.g. news/show/id/regex:|(\d)*?|/action/remove or something like that, but in fact, thats some kind of input validation... dont know if needed
/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Route_Dynamic implements Chrome_Router_Route_Interface
{
    /**
     * How much slashes "/" are allowed in a url?
     *
     * @var int
     */
    const CHROME_ROUTE_REGEX_MAX_LEVEL = 30;

    private $_resources = array();

    protected $_GET = array();

    private $_previousKey = null;

    private $_resourceID = null;

    protected $_model = null;

    protected $_resource = null;

    public function __construct(Chrome_Model_Abstract $model)
    {
        $this->_model = $model;
        Chrome_Router::getInstance()->addRouterClass($this);

        try {
            Chrome_Registry::getInstance()->set(Chrome_Router_Interface::CHROME_ROUTER_REGISTRY_NAMESPACE,
                'Chrome_Route_Dynamic', $this, false);
        }
        catch (Chrome_Exception $e) {
            unset($e);
            // do nothing
        }

    }

    public function match(Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data)
    {
        $array = explode('/', $url->getPath(), self::CHROME_ROUTE_REGEX_MAX_LEVEL);

        if(count($array) <= 1) {
            return false;
        }

        $this->_resources = $this->_model->getResourcesAsArray();

        if($this->_exist($array, $this->_resources) !== false) {

            $resource = $this->_model->getResourceByID($this->_resourceID);

            if($resource === null or $resource == false) {
                throw new Chrome_Exception('Path matched, but resource wasn\'t found!', 2004);
            }

            $this->_resource = new Chrome_Router_Resource();
            $this->_resource->setClass($resource['class']);
            $this->_resource->setFile($resource['file']);

            if(count($resource['GET']) > 0) {
                $data->setGET($resource['GET']);
            }
            if(count($resource['POST']) > 0) {
                $data->setPOST($resource['POST']);
            }

            return true;

        } else {
            return false;
        }
    }

    private function _exist($array, array $resource)
    {

        foreach($resource as $key => $value) {

            // found if:
            // 1. the keys are matching
            // 2. the key(from resource) is * (so every input is allowed, but there must be an input)
            // 3. the input array is empty, but the resource isnt and there is a key ""
            if(isset($array[0]) and $key === $array[0] or ($key === '*' and $array[0] != '') or (count($array) === 0 and
                array_key_exists('', $resource))) {

                if($key === '*') {
                    $this->_GET[$this->_previousKey] = $array[0];
                }

                if(is_array($value)) {
                    $this->_previousKey = array_shift($array);

                    return $this->_exist($array, $resource[$key]);
                } else {
                    $this->_resourceID = $value;
                    return true;
                }
            } else {
                continue;
            }
        }
        return false;
    }

    public function getResource()
    {
        return $this->_resource;
    }

    public function url(Chrome_Router_Resource_Interface $resource)
    {
        die('Not implemented yet');
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Model_Route_Dynamic extends Chrome_Model_Abstract
{
    private static $_instance = null;

    protected function __construct()
    {
        $this->_decorator = new Chrome_Model_Route_Dynamic_Cache(new Chrome_Model_Route_Dynamic_DB());
    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Model_Route_Dynamic_Cache extends Chrome_Model_Cache_Abstract
{
    const CHROME_MODEL_ROUTER_DYNAMIC_CACHE_CACHE_FILE = 'tmp/cache/router/_dynamic.cache';

    protected function _cache()
    {
        $this->_cache = parent::$_cacheFactory->factory('serialization', self::
            CHROME_MODEL_ROUTER_DYNAMIC_CACHE_CACHE_FILE);
    }

    public function getResourceByID($id)
    {

        if(($return = $this->_cache->load('getResource_' . $id)) === null) {

            $return = $this->_decorator->getResourceByID($id);

            if($return !== false) {
                $this->_cache->save('getResource_' . $id, $return);
            }
        }

        return $return;
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Model_Route_Dynamic_DB extends Chrome_Model_Database_Abstract
{
    protected $_dbInterface = 'model';

    public function __construct()
    {
        parent::__construct();
    }

    public function getResourcesAsArray()
    {
        // just an example
        return array('site' => array('news' => array('id' => array('*' => array(
                            '' => 1, // '' is an alias for 'show'
                            'show' => 1,
                            'update' => 2)))));
    }

    public function getResourceByID($id)
    {
        $id = (int)$id;

        $result = $this->_dbInterfaceInstance->prepare('routeDynamicGetResourceById')
            ->execute(array($id));

        $row = $result->getNext();

        $this->_dbInterfaceInstance->clear();

        // translate key=value,key2=value2 into an array {key => value, key2=>value2}
        $GET = array();
        if(!empty($row['GET'])) {

            // input is like key=value,key2=value2,..
            $keyValuePairs = explode(',', $row['GET']);
            foreach($keyValuePairs as $keyValuePair) {

                $keyValue = explode('=', $keyValuePair);
                $GET[$keyValue[0]] = $keyValue[1];
            }
        }
        $row['GET'] = $GET;

        $POST = array();
        if(!empty($row['POST'])) {

            // input is like key=value,key2=value2,..
            $keyValuePairs = explode(',', $row['POST']);
            foreach($keyValuePairs as $keyValuePair) {

                $keyValue = explode('=', $keyValuePair);
                $POST[$keyValue[0]] = $keyValue[1];
            }
        }
        $row['POST'] = $POST;


        return $row;
    }
}
