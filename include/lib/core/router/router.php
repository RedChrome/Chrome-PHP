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
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 */

namespace Chrome\Router;

use \Chrome\URI\URI_Interface;
/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Router_Interface extends \Chrome\Router\Route\Route_Interface, \Chrome\Exception\Processable_Interface
{
    public function route(URI_Interface $url, \Chrome\Request\Data_Interface $data);

    public function addRoute(\Chrome\Router\Route\Route_Interface $obj);
}


/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Result_Interface
{
    public function setFile($file);

    public function getFile();

    public function setClass($class);

    public function getClass();

    public function setName($name);

    public function getName();
}


/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Result implements Result_Interface
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
class Router implements Router_Interface
{
    protected $_routeInstance = null;
    protected $_routerClasses = array();
    protected $_result = null;
    protected $_exceptionHandler = null;

    public function __construct()
    {
    }

    public function match(URI_Interface $url, \Chrome\Request\Data_Interface $data)
    {
        try
        {
            foreach($this->_routerClasses as $router)
            {
                if($router->match($url, $data) === true)
                {
                    $this->_result = $router->getResult();

                    break;
                }
            }

            if($this->_result == null or !($this->_result instanceof \Chrome\Router\Result_Interface))
            {

                // already tried to route to 404.html and not found... there is smt. wrong!
                if($url->getPath() === '404.html')
                {
                    throw new \Chrome\Exception('Could not found adequate controller class!', 2001);
                }

                // todo: is this okay?
                $url = new \Chrome\URI\URI();
                $url->setPath('404.html');
                $this->match($url, $data);
            }
        } catch(\Chrome\Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }
    }

    public function route(URI_Interface $url, \Chrome\Request\Data_Interface $data)
    {
        // replace ROOT,
        $path = ltrim(preg_replace('#\A' . ROOT_URL . '#', '', '/'.$url->getPath()), '/');
        $url->setPath($path);

        try
        {
            $this->match($url, $data);
        } catch(\Chrome\Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }

        return $this->_result;
    }

    public function getResult()
    {
        return $this->_result;
    }

    public function addRoute(\Chrome\Router\Route\Route_Interface $obj)
    {
        $this->_routerClasses[] = $obj;
    }

    public function setExceptionHandler(\Chrome\Exception\Handler_Interface $obj)
    {
        $this->_exceptionHandler = $obj;
    }

    public function getExceptionHandler()
    {
        return $this->_exceptionHandler;
    }
}


namespace Chrome\Router\Route;

use \Chrome\Logger\Loggable_Interface;
use \Psr\Log\LoggerInterface;
use \Chrome\URI\URI_Interface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Route_Interface
{
    public function match(URI_Interface $url, \Chrome\Request\Data_Interface $data);

    public function getResult();
}


abstract class AbstractRoute implements Route_Interface, Loggable_Interface
{
    protected $_logger = null;
    protected $_model = null;
    protected $_result = null;

    public function __construct(\Chrome_Model_Abstract $model, LoggerInterface $logger)
    {
        $this->_model = $model;
        $this->setLogger($logger);
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    public function getLogger()
    {
        return $this->_logger;
    }

    public function getResult()
    {
        return $this->_result;
    }
}
