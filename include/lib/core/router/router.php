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
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 17:39:14] --> $
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Chrome_Router_Interface extends Chrome_Router_Route_Interface, Chrome_Exception_Processable_Interface
{
    public function route(Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data);

    public function addRoute(Chrome_Router_Route_Interface $obj);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Chrome_Router_Route_Interface
{
    public function match(Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data);

    public function getResource();

    public function url(Chrome_Router_Resource_Interface $resource);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Chrome_Router_Result_Interface
{
    public function setFile($file);

    public function getFile();

    public function setClass($class);

    public function getClass();

    public function setName($name);

    public function getName();
}
interface Chrome_Router_Resource_Interface
{
    public function getName();
    // TODO: finish interface with better names
    // public function getRequestOptions();
    public function setReturnAsAbsolutPath($boolean);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Router_Resource implements Chrome_Router_Result_Interface
{
    protected $_file = null;

    protected $_class = null;

    protected $_name = null;

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

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Router implements Chrome_Router_Interface
{

    protected $_routeInstance = null;

    protected $_routerClasses = array();

    protected $_resource = null;

    protected $_exceptionHandler = null;

    public function __construct()
    {
    }

    public function match(Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data)
    {
        try
        {
            foreach($this->_routerClasses as $router)
            {
                if($router->match($url, $data) === true)
                {
                    $this->_resource = $router->getResource();

                    break;
                }
            }

            if($this->_resource == null or !($this->_resource instanceof Chrome_Router_Result_Interface))
            {

                // already tried to route to 404.html and not found... there is smt. wrong!
                if($url->getPath() === '404.html')
                {
                    throw new Chrome_Exception('Could not found adequate controller class!', 2001);
                }

                $url = new Chrome_URI();
                $url->setPath('404.html');
                $this->match($url, $data);
            }
        } catch(Chrome_Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    public function route(Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data)
    {
        // replace ROOT,
        $path = ltrim(preg_replace('#\A' . ROOT_URL . '#', '', '/'.$url->getPath()), '/');
        $url->setPath($path);

        try
        {
            $this->match($url, $data);
        } catch(Chrome_Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }

        return $this->_resource;
    }

    public function getResource()
    {
        return $this->_resource;
    }

    public function addRoute(Chrome_Router_Route_Interface $obj)
    {
        $this->_routerClasses[] = $obj;
    }

    public function setExceptionHandler(Chrome_Exception_Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }

    public function url(Chrome_Router_Resource_Interface $resource)
    {
        foreach($this->_routerClasses as $router)
        {
            if(($return = $router->url($name, $options)) !== false)
            {
                return $return;
            }
        }
    }
}
