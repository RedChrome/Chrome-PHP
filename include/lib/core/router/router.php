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
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
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
    public function setClass($class);

    public function getClass();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Result implements Result_Interface
{
    protected $_class = null;

    public function __construct()
    {
    }

    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function getClass()
    {
        return $this->_class;
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
        if($this->_doMatch($url, $data) !== true OR !$this->_isSuccessfullyMatched()) {
            // this should not happen. The application should set always a route handler which does always find a route. -> FallbackRoute
            throw new \Chrome\Exception('Could match given url', 2001);
        }
    }

    protected function _isSuccessfullyMatched()
    {
        return $this->_result != null AND ($this->_result instanceof \Chrome\Router\Result_Interface);
    }

    protected function _doMatch(URI_Interface $url, \Chrome\Request\Data_Interface $data)
    {
        try {
            foreach($this->_routerClasses as $router)
            {
                if($router->match($url, $data) === true)
                {
                    $this->_result = $router->getResult();
                    return true;
                }
            }
        } catch(\Chrome\Exception $e)
        {
            $this->_exceptionHandler->exception($e);
        }

        return false;
    }

    public function route(URI_Interface $url, \Chrome\Request\Data_Interface $data)
    {
        // replace ROOT,
        $path = ltrim(preg_replace('#\A' . ROOT_URL . '#', '', '/'.$url->getPath()), '/');
        #var_dump($url, $path, $data->getGETData());
        $url->setPath($path);

        $this->match($url, $data);

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

    /**
     * @return \Chrome\Router\Result_Interface
     */
    public function getResult();
}


abstract class AbstractRoute implements Route_Interface, Loggable_Interface
{
    protected $_logger = null;
    protected $_model = null;
    protected $_result = null;

    public function __construct(\Chrome\Model\Model_Interface $model, LoggerInterface $logger)
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