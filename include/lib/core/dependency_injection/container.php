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
 * @subpackage Chrome.DependencyInjection
 */

namespace Chrome\DI;

use \Chrome\DI\Exception\NotFoundException;
use \Chrome\DI\Exception\ContainerException;

require_once 'loader/loader.php';

/**
 * A Dependency injection container using Handlers
 *
 * The internal resolving logic is seperated to different dependecy injection handlers.
 *  *
 * @package CHROME-PHP
 * @subpackage Chrome.DependencyInjection
 */
interface Container_Interface extends \Interop\Container\ContainerInterface
{
    /**
     * Attaches a handler.
     *
     * A handler is used to actually get the dependency.
     *
     * @param string $handlerName
     * @param Handler_Interface $handler
     */
    public function attachHandler($handlerName, Handler_Interface $handler);

    public function detachHandler($handlerName);

    public function getHandler($handlerName);

    public function isAttachedHandler($handlerName);

    public function remove($key);

    public function attachInvoker($invokerName, Invoker_Interface $invoker);

    public function detachInvoker($invokerName);

    public function getInvoker($invokerName);

    public function isAttachedInvoker($invokerName);
}

interface Handler_Interface
{
    /**
     * Gets the dependency, defined as $key
     *
     * If not found, it return null.
     * If found, it returns anything else
     *
     * @param string $key
     * @param Container_Interface $container
     * @return mixed
     */
    public function get($key, Container_Interface $container);

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $key Identifier of the entry to look for.
     * @return boolean
     */
    public function has($key);

    public function remove($key);
}

interface Invoker_Interface
{
    /**
     * Invokes any method on the object $object.
     *
     * E.g. LoggerAwareInterfaceInvoker calls setLogger on
     * each object which is an instance of LoggerAwareInterface.
     *
     * @param mixed $object
     * @param Container_Interface $container
     * @return void
     */
    public function invoke($object, Container_Interface $container);
}

class Container implements Container_Interface
{
    protected $_handlers = array();

    protected $_handlersNames = array();

    protected $_currentHandler = 0;

    protected $_invokers = array();

    protected $_invokersNames = array();

    protected $_currentInvoker = 0;

    public function attachHandler($handlerName, Handler_Interface $handler)
    {
        if(!is_string($handlerName)) {
            throw new \Chrome\InvalidArgumentException('Argument $handlerName must be of type string');
        }

        $this->_handlers[$this->_currentHandler] = $handler;
        $this->_handlersNames[$handlerName] = $this->_currentHandler;
        $this->_currentHandler++;
    }

    public function detachHandler($handlerName)
    {
        if(!$this->isAttachedHandler($handlerName)) {
            return;
        }

        $key = $this->_handlersNames[$handlerName];
        unset($this->_handlers[$key], $this->_handlersNames[$handlerName]);
    }

    public function getHandler($handlerName)
    {
        if(!$this->isAttachedHandler($handlerName)) {
            throw new ContainerException('No handler with name "'.$handlerName.'" defined');
        }

        return $this->_handlers[$this->_handlersNames[$handlerName]];
    }

    public function isAttachedHandler($handlerName)
    {
        return isset($this->_handlersNames[$handlerName]);
    }

    public function get($key)
    {
        try {
            foreach($this->_handlers as $handler)
            {
                $object = $handler->get($key, $this);

                if($object !== null) {

                    foreach($this->_invokers as $invoker) {
                        $invoker->invoke($object, $this);
                    }

                    return $object;
                }
            }
        } catch(\Chrome\Exception $e) {
            throw new ContainerException('Could not retrieve object with key "'.$key.'". An exception occured', 0, $e);
        }

        throw new NotFoundException('Identifier "'.$key.'" is not defined');
    }

    public function has($key)
    {
        foreach($this->_handlers as $handler)
        {
            if($handler->has($key)) {
                return true;
            }
        }

        return false;
    }

    public function remove($key)
    {
        foreach($this->_handlers as $handler) {
            $handler->remove($key);
        }
    }

    public function getInvoker($invokerName)
    {
        if(!$this->isAttachedInvoker($invokerName)) {
            throw new ContainerException('No handler with name "'.$invokerName.'" defined');
        }

        return $this->_invokers[$this->_invokersNames[$invokerName]];
    }

    public function attachInvoker($invokerName, Invoker_Interface $invoker)
    {
        if(!is_string($invokerName)) {
            throw new \Chrome\InvalidArgumentException('Argument $invokerName must be of type string');
        }

        $this->_invokers[$this->_currentInvoker] = $invoker;
        $this->_invokersNames[$invokerName] = $this->_currentInvoker;
        $this->_currentInvoker++;
    }

    public function detachInvoker($invokerName)
    {
        if(!$this->isAttachedInvoker($invokerName)) {
            return;
        }

        $key = $this->_invokersNames[$invokerName];
        unset($this->_invokers[$key], $this->_invokersNames[$invokerName]);
    }

    public function isAttachedInvoker($invokerName)
    {
        return isset($this->_invokersNames[$invokerName]);
    }
}

namespace Chrome\DI\Exception;

class ContainerException extends \Chrome\Exception implements \Interop\Container\Exception\ContainerException
{

}

class NotFoundException extends \Chrome\Exception implements \Interop\Container\Exception\NotFoundException
{

}