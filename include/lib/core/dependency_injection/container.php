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

require_once 'loader/loader.php';

interface Container_Interface
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

    public function get($key);

    public function remove($key);
}

interface Handler_Interface
{
    /**
     * Gets the dependency, defined as $key
     *
     * @param string $key
     * @param Container_Interface $container
     */
    public function get($key, Container_Interface $container);

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
        if(!$this->_isAttached($handlerName)) {
            return;
        }

        $key = $this->_handlersNames[$handlerName];
        unset($this->_handlers[$key], $this->_handlersNames[$handlerName]);
    }

    public function getHandler($handlerName)
    {
        if(!$this->isAttached($handlerName)) {
            throw new \Chrome\InvalidArgumentException('No handler with name "'.$handlerName.'" defined');
        }

        return $this->_handlers[$this->_handlersNames[$handlerName]];
    }

    public function isAttached($handlerName)
    {
        return isset($this->_handlersNames[$handlerName]);
    }

    public function get($key)
    {
        foreach($this->_handlers as $handler)
        {
            $object = $handler->get($key, $this);

            if($object !== null) {
                return $object;
            }
        }

        throw new \Chrome\Exception('Identifier "'.$key.'" is not defined');
    }

    public function remove($key)
    {
        foreach($this->_handlers as $handler) {
            $handler->remove($key);
        }
    }
}