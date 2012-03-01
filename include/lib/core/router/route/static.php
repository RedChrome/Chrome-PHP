<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Router
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2011 23:35:17] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
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
        Chrome_Router::getInstance()->addRouterClass($this);
        try {
            Chrome_Registry::getInstance()->set(Chrome_Router_Interface::CHROME_ROUTER_REGISTRY_NAMESPACE, 'Chrome_Route_Static', $this, false);
        } catch(Chrome_Exception $e) {
            unset($e);
            // do nothing
        }
    }

    public function match(Chrome_URI_Interface $url) {

        $row = $this->_model->getRoute($url->getPath());

        if($row == false) {
            return false;
        } else {
            
            $this->_resource = new Chrome_Router_Resource();
            
            $this->_resource->setFile($row['file']);
            $this->_resource->setClass($row['class']);
            
            if($row['GET_key'] !== '' AND $row['GET_value'] !== '') {
                $this->_resource->setGET(array_combine(explode(',', $row['GET_key']), explode(',', $row['GET_value'])));
            }
            
            return true;
        }
    }

    public function getResource()
    {
        return $this->_resource;
    }
    
    public function url($name, array $options)
    {
        $row = $this->_model->findRoute($name);
        
        if($row == false) {
            return $row;
        } else {
            return $row['search'];
        }
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
class Chrome_Model_Route_Static extends Chrome_Model_Abstract
{
    private static $_instance = null;

    protected function __construct()
    {
        $this->_decorator = new Chrome_Model_Route_Static_Cache(new Chrome_Model_Route_Static_DB());
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
class Chrome_Model_Route_Static_Cache extends Chrome_Model_Cache_Abstract
{
    const CHROME_MODEL_ROUTE_STATIC_CACHE_CACHE_FILE = 'tmp/cache/router/_static.cache';

    protected function _cache()
    {
        $this->_cache = parent::$_cacheFactory->factory('serialization', self::CHROME_MODEL_ROUTE_STATIC_CACHE_CACHE_FILE);
    }

    public function getRoute($search) {

        if(($return = $this->_cache->load('getRoute_'.$search)) === null) {

            $return = $this->_decorator->getRoute($search);

            if($return !== false) {
                $this->_cache->save('getRoute_'.$search, $return);
            }
        }

        return $return;
    }
    
    public function findRoute($search) {

        if(($return = $this->_cache->load('findRoute_'.$search)) === null) {

            $return = $this->_decorator->findRoute($search);

            if($return !== false) {
                $this->_cache->save('findRoute_'.$search, $return);
            }
        }

        return $return;
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
class Chrome_Model_Route_Static_DB extends Chrome_Model_DB_Abstract
{
    protected $_dbInterface = 'interface';

    public function __construct() {
        parent::__construct();
    }

    public function getRoute($search) {

        $this->_dbInterfaceInstance
                ->select('*')
                ->from('route_static')
                ->where('search = "'.$this->_escape($search).'"')
                ->limit(0, 1)
                ->execute();

        $row = $this->_dbInterfaceInstance->next();
        $this->_dbInterfaceInstance->clear();

        return $row;
    }
    
    public function findRoute($name) {
        
        $this->_dbInterfaceInstance
                ->select('search')
                ->from('route_static')
                ->where('name = "'.$this->_escape($name).'"')
                ->limit(0, 1)
                ->execute();

        $row = $this->_dbInterfaceInstance->next();
        $this->_dbInterfaceInstance->clear();

        return $row;
    }
}