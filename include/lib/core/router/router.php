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

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Router_Interface extends \Chrome\Exception\Processable_Interface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return Result_Interface
     */
    public function route(\Psr\Http\Message\ServerRequestInterface $request);

    public function addRoute(\Chrome\Router\Route\Route_Interface $obj);

    /**
     * Sets the base path for routing.
     *
     * @param string $basepath
     */
    public function setBasepath($basepath);

    /**
     * @return \Chrome\Router\Result_Interface
     */
    public function getResult();
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

    public function getRequest();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Result implements Result_Interface
{
    protected $_class = null;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $_request = null;

    public function __construct()
    {
    }

    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function setRequest(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $this->_request = $request;
    }

    public function getClass()
    {
        return $this->_class;
    }

    public function getRequest()
    {
        return $this->_request;
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Router implements Router_Interface
{
    use \Chrome\Exception\ProcessableTrait;

    protected $_routeInstance = null;
    protected $_routerClasses = array();
    protected $_result = null;
    protected $_basepath = '';

    public function __construct()
    {
    }

    public function setBasepath($basepath)
    {
        $this->_basepath = (string) $basepath;
    }

    /*
    public function match(\Psr\Http\Message\ServerRequestInterface $request)
    {
        if($this->_doMatch($request) !== true OR !$this->_isSuccessfullyMatched()) {
            // this should not happen. The application should set always a route handler which does always find a route. -> FallbackRoute
            throw new \Chrome\Exception('Could match given url', 2001);
        }
    }
    */
    protected function _isSuccessfullyMatched()
    {
        return $this->_result != null AND ($this->_result instanceof \Chrome\Router\Result_Interface);
    }

    protected function _doMatch(\Psr\Http\Message\ServerRequestInterface $request, $normalizedPath)
    {
        try {
            foreach($this->_routerClasses as $router)
            {
                if($router->match($request, $normalizedPath) === true)
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

    public function route(\Psr\Http\Message\ServerRequestInterface $request)
    {
        // replace ROOT,
        $path = preg_replace('#\A' . $this->_basepath . '#', '', $request->getUri()->getPath());

        $this->_doMatch($request, $path);

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
}


namespace Chrome\Router\Route;

use \Chrome\Logger\Loggable_Interface;
use \Psr\Log\LoggerInterface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Route_Interface
{
    public function match(\Psr\Http\Message\ServerRequestInterface $request, $normalizedPath);

    /**
     * @return \Chrome\Router\Result_Interface
     */
    public function getResult();
}


abstract class AbstractRoute implements Route_Interface, Loggable_Interface
{
    use \Chrome\Logger\LoggableTrait;

    protected $_model = null;
    protected $_result = null;

    public function __construct(\Chrome\Model\Model_Interface $model, LoggerInterface $logger)
    {
        $this->_model = $model;
        $this->setLogger($logger);
    }

    public function getResult()
    {
        return $this->_result;
    }

    protected function _applyGetAndPost(\Psr\Http\Message\ServerRequestInterface $request, $get, $post)
    {
        if($get !== null) {
            $request = $request->withQueryParams($get);
        }

        if($post !== null) {
            $request = $request->withParsedBody($post);
        }

        return $request;
    }
}