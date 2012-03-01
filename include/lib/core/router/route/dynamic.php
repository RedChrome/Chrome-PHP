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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.10.2011 12:31:46] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

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
        Chrome_Registry::getInstance()->set(Chrome_Router_Interface::CHROME_ROUTER_REGISTRY_NAMESPACE, 'Chrome_Route_Dynamic', $this, false);
    }

    public function match(Chrome_URI_Interface $url)
    {
        $this->_resources = $this->_model->getResourcesAsArray();

        $array = explode('/', $url->getPath(), self::CHROME_ROUTE_REGEX_MAX_LEVEL);

        if(sizeof($array) <= 1) {
            return false;
        }

        if($this->_exist($array, $this->_resources) !== false) {
            
            $resource = $this->_model->getResourceByID($this->_resourceID);
            
            if($resource === null OR $resource == false) {
                throw new Chrome_Exception('Patch matched, but resource wasn\'t found!', 2004);
            }
                        
            $this->_resource = new Chrome_Router_Resource();
            $this->_resource->setClass($resource['class']);
            $this->_resource->setFile($resource['file']);
        
            $this->_resource->setGET(array_merge($this->_GET, $resource['GET']));
            
            return true;
            
        } else {
            return false;
        }
    }

    private function _exist($array, array $resource) {
        
        foreach($resource AS $key => $value) {
            
            if(isset($array[0]) AND $key === $array[0] OR ($key === '*' AND $array[0] != '')) {
                
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
    
    public function url($name, array $options)
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
        $this->_cache = parent::$_cacheFactory->factory('serialization', self::CHROME_MODEL_ROUTER_DYNAMIC_CACHE_CACHE_FILE);
    }

    public function getResourceByID($id) {

        if(($return = $this->_cache->load('getResource_'.$id)) === null) {

            $return = $this->_decorator->getResourceByID($id);

            if($return !== false) {
                $this->_cache->save('getResource_'.$id, $return);
            }
        }

        return $return;
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
class Chrome_Model_Route_Dynamic_DB extends Chrome_Model_DB_Abstract
{
    protected $_dbInterface = 'interface';

    public function __construct() {
        parent::__construct();
    }
    
    public function getResourcesAsArray() {
        return array('site' => array('news' => array('id' => array('*' => array('' => 1, 'show' => 1, 'update' => 2)))));
    }
    
    public function getResourceByID($id) {
        $id = (int) $id;
        
        $this->_dbInterfaceInstance
                ->select('*')
                ->from('route_dynamic')
                ->where('id = "'.$id.'"')
                ->limit(0, 1)
                ->execute();
        
        $row = $this->_dbInterfaceInstance->next();
        $this->_dbInterfaceInstance->clear();
        
        $array = array();
        if($row['GET_key'] !== '' AND $row['GET_value'] !== '') {
                $array = array_combine(explode(',', $row['GET_key']), explode(',', $row['GET_value']));
        }
        $row['GET'] = $array;
        
        unset($row['GET_key'], $row['GET_value']);
        
        return $row;        
    }
}