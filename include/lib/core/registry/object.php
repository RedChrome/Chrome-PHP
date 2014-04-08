<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.Application
 */

namespace Chrome\Registry;

interface Object
{
    const DEFAULT_OBJECT = 'default';

    public function get($key = self::DEFAULT_OBJECT);

    public function getAll();

    public function remove($key);

    public function has($key);
}

abstract class Object_Abstract implements Object
{
    protected $_registry = array();

    public function get($key = self::DEFAULT_OBJECT)
    {
        if($this->has($key))
        {
            return $this->_registry[$key];
        }

        return $this->_objectNotFound($key);
    }

    public function getAll()
    {
        return $this->_registry;
    }

    public function remove($key)
    {
        unset($this->_registry[$key]);
    }

    public function has($key)
    {
        return isset($this->_registry[$key]);
    }

    protected function _objectNotFound($key)
    {
        throw new \Chrome\Exception('No Object found with key "'.$key.'"');
    }

    protected function _set($key, $value)
    {
        $this->_registry[$key] = $value;
    }
}

/**
 * Implementation of {@link Object}
 *
 * This is a simple registry, which only contains at most one
 * element, namely {@link Object::DEFAULT_OBJECT}. Every {@link
 * Object_Simple_Abstract::get()} will return only the default object.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Registry
 */
abstract class Object_Single_Abstract implements Object
{
    protected $_registry = array();

    public function get($key = self::DEFAULT_OBJECT)
    {
        $key = self::DEFAULT_OBJECT;

        if($this->has($key))
        {
            return $this->_registry[$key];
        }

        return $this->_objectNotFound($key);
    }

    protected function _objectNotFound($key)
    {
        throw new \Chrome\Exception('No Object found with key "'.$key.'"');
    }

    protected function _set($value)
    {
        $this->_registry[self::DEFAULT_OBJECT] = $value;
    }

    public function getAll()
    {
        return $this->_registry;
    }

    public function remove($key)
    {
        $key = self::DEFAULT_OBJECT;
        unset($this->_registry[$key]);
    }

    public function has($key)
    {
        $key = self::DEFAULT_OBJECT;
        return isset($this->_registry[$key]);
    }
}