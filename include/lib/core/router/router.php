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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.10.2011 12:31:34] --> $
 * @author     Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 * @see core/error/exception/router.php
 */ 
require_once LIB.'exception/router.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
interface Chrome_Router_Interface
{
    const CHROME_ROUTER_REGISTRY_NAMESPACE = 'Chrome_Router';
    
    public function route(Chrome_URI_Interface $url);

    public function match(Chrome_URI_Interface $url);

    public function getResource();
    
    public function url($name, array $options);
    
    public function addRouterClass(Chrome_Router_Route_Interface $obj);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
interface Chrome_Router_Route_Interface
{
    public function match(Chrome_URI_Interface $url);

    public function getResource();
    
    public function url($name, array $options);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
interface Chrome_Router_Resource_Interface
{
    public function setFile($file);

    public function getFile();

    public function setClass($class);

    public function getClass();
    
    public function setGET(array $array);
    
    public function getGET();
    
    public function initClass();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
class Chrome_Router_Resource implements Chrome_Router_Resource_Interface
{
    protected $_file = null;

    protected $_class = null;
    
    protected $_get = array();
    
    public function __construct()
    {
    }

    public function setFile($file)
    {
        $this->_file = $file;
    }

    public function getFile()
    {
        return $this->_file;
    }

    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function getClass()
    {
        return $this->_class;
    }
    
    public function setGET(array $array)
    {
        $this->_get = array_merge($this->_get, $array);
    }
    
    public function getGET()
    {
        return $this->_get;
    }
    
    public function initClass() {
        
        if($this->_class == '' OR empty($this->_class)) {
            throw new Chrome_Exception('No Class set in Chrome_Router_Resource!', 2002);
        }
        
        foreach($this->_get AS $key => $value) {
            $_GET[$key] = $value;
        }
        
        if(!class_exists($this->_class, false)) {
            
            $file = $this->_file;
            
            if($file != '' AND _isFile(BASEDIR.$file)) {
                require_once BASEDIR.$file;
            } else {
                
                try {
                    import($this->_class);
                } catch(Chrome_Exceptopm $e) {
                    throw new Chrome_Exception('No file found AND could no find the corresponding file!', 2003);
                }
                Chrome_Log::log('class "'.$this->_class.'" were found by autoloader! But it should inserted into db to speed up website!', E_NOTICE);
            } 
        }
        
        return new $this->_class();
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
class Chrome_Router implements Chrome_Router_Interface, Chrome_Exception_Processable_Interface
{
    private static $_instance = null;

    protected $_routeInstance = null;

    protected $_routerClasses = array();

    protected $_resource = null;

    protected $_exceptionHandler = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function match(Chrome_URI_Interface $url)
    {
        try {
            foreach($this->_routerClasses AS $router) {
                if($router->match($url) === true) {
                    $this->_resource = $router->getResource();

                    break;
                }
            }

            if($this->_resource == null OR !($this->_resource instanceof Chrome_Router_Resource_Interface)) {
                throw new Chrome_Exception('Could not found adequate controller class!', 2001);
            }

        }
        catch (Chrome_Exception $e) {          
            $this->_exceptionHandler->exception($e);
        }
    }

    public function route(Chrome_URI_Interface $url)
    {
        // replace ROOT,
        $path = ltrim(preg_replace('#\A'.ROOT_URL.'#', '', '/'.$url->getPath()), '/');
        $url->setPath($path);

        try {

            $this->match($url);

        }
        catch (Chrome_Exception $e) {
            $this->_exceptionHandler->exception($e);
        }

        return $this->_resource;
    }

    public function getResource()
    {
        return $this->_resource;
    }
    
    public function addRouterClass(Chrome_Router_Route_Interface $obj)
    {
        $this->_routerClasses[] = $obj;
    }

    public function setExceptionHandler(Chrome_Exception_Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;

        return $this;
    }

    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }
    
    public function url($name, array $options)
    {
        foreach($this->_routerClasses AS $router) {
            if( ($return = $router->url($name, $options)) !== false) {
                return $return;
            } 
        }
    }
}