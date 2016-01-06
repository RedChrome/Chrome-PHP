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

    public function isAttached($handlerName);

    public function remove($key);
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



class Container implements Container_Interface
{
    protected $_handlers = array();

    protected $_handlersNames = array();

    protected $_current = 0;

    public function __construct()
    {

    }

    public function attachHandler($handlerName, Handler_Interface $handler)
    {
        if(!is_string($handlerName)) {
            throw new \Chrome\InvalidArgumentException('Argument $handlerName must be of type string');
        }

        $this->_handlers[$this->_current] = $handler;
        $this->_handlersNames[$handlerName] = $this->_current;
        $this->_current++;
    }

    public function detachHandler($handlerName)
    {
        if(!$this->isAttached($handlerName)) {
            return;
        }

        $key = $this->_handlersNames[$handlerName];
        unset($this->_handlers[$key], $this->_handlersNames[$handlerName]);
    }

    public function getHandler($handlerName)
    {
        if(!$this->isAttached($handlerName)) {
            throw new ContainerException('No handler with name "'.$handlerName.'" defined');
        }

        return $this->_handlers[$this->_handlersNames[$handlerName]];
    }

    public function isAttached($handlerName)
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
                    return $object;
                }
            }
        } catch(\Crome\Exception $e) {
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
}

namespace Chrome\DI\Exception;

class ContainerException extends \Chrome\Exception implements \Interop\Container\Exception\ContainerException
{

}

class NotFoundException extends \Chrome\Exception implements \Interop\Container\Exception\NotFoundException
{

}