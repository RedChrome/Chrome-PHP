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
 * @subpackage Chrome.Registry
 */

namespace Chrome\Registry;

/**
 * Interface for a protected registry.
 *
 * A protected registry can save values which cannot get overwritten.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Registry
 */
interface ProtectedRegistry_Interface
{

    /**
     * Adds a value to the registry
     *
     * @param string $namespace
     *        namespace of the entry
     * @param string $key
     *        key of the entry
     * @param mixed $value
     *        value of the entry
     * @param bool $readOnly
     *        true: value is read only | false: value can get modified
     * @throws \Chrome\Exception if trying to modifie a protected entry
     * @return bool true on success
     */
    public function set($namespace, $key, $value, $readOnly = false);

    /**
     * Retrieves a value from the registry.
     *
     * @param mixed $namespace
     *        namespace of the entry
     * @param mixed $key
     *        key of the entry
     * @return mixed value OR null
     */
    public function get($namespace, $key);

    /**
     * Returns the protection for an entry
     *
     * @param mixed $namespace
     *        namespace of the entry
     * @param mixed $key
     *        key of the entry
     * @return bool true: entry is protected false else
     */
    public function isProtected($namespace, $key);

    /**
     * Determines whether the registry entry is set
     *
     * @param mixed $namespace
     *        namespace of the entry
     * @param mixed $key
     *        key of the entry
     * @return bool true: entry isset
     */
    public function _isset($namespace, $key);

    /**
     * Unsets a registry entry
     * if entry is protected, then it throws a \Chrome\Exception
     *
     * @throws \Chrome\Exception
     * @param mixed $namespace
     *        namespace of the entry
     * @param mixed $key
     *        key of the entry
     * @return void
     */
    public function _unset($namespace, $key);
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Registry
 */
abstract class AbstractProtectedRegistry implements ProtectedRegistry_Interface
{
    /**
     * Contains the registry storage
     *
     * @var array
     */
    protected $_registry = array();

    /**
     * Chrome_Registry_Abstract::__construct()
     *
     * Singleton pattern
     *
     * @return s Chrome_Registry_Abstract
     */
    protected function __construct()
    {
    }

    /**
     * Chrome_Registry_Abstract::__clone()
     *
     * Does nothing, for singleton pattern
     */
    final private function __clone()
    {
    }
}

/**
 * Implementation of a protected registry
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Registry
 * @author Alexander Book
 * @copyright 2009 by Alexander Book
 * @version 2009
 * @access public
 */
class ProtectedRegistry extends AbstractProtectedRegistry
{
    /**
     * Chrome_Registry::set()
     *
     * Adds a value to the registry
     *
     * @param string $namespace
     *        namespace of the entry
     * @param string $key
     *        key of the entry
     * @param mixed $value
     *        value of the entry
     * @param bool $readOnly
     *        true: value is read only | false: value can get modified
     * @throws \Chrome\Exception if trying to modifie a protected entry
     * @return bool true on success
     */
    public function set($namespace, $key, $value, $readOnly = false)
    {
        if(isset($this->_registry[$namespace][$key]))
        {
            if($this->_registry[$namespace][$key]['readOnly'] === true)
            {
                throw new \Chrome\Exception('The entry with name "' . $name . '" already exists in namespace "' . $namespace . '" AND is read only! Cannot modify it!');
            }

            $this->_registry[$namespace][$key]['value'] = $value;
            $this->_registry[$namespace][$key]['readOnly'] = $readOnly;
        } else
        {

            $this->_registry[$namespace][$key]['value'] = $value;
            $this->_registry[$namespace][$key]['readOnly'] = $readOnly;
        }

        return true;
    }

    /**
     * Chrome_Registry::get()
     *
     * Retrieves a value from the registry.
     *
     * @param mixed $namespace
     *        namespace of the entry
     * @param mixed $key
     *        key of the entry
     * @return mixed value OR null
     */
    public function get($namespace, $key)
    {
        if(isset($this->_registry[$namespace][$key]))
            return $this->_registry[$namespace][$key]['value'];
        else return null;
    }

    /**
     * Chrome_Registry::isProtected()
     *
     * Returns the protection for an entry
     *
     * @param mixed $namespace
     *        namespace of the entry
     * @param mixed $key
     *        key of the entry
     * @return bool true: entry is protected false else
     */
    public function isProtected($namespace, $key)
    {
        if(isset($this->_registry[$namespace][$key]))
        {
            if($this->_registry[$namespace][$key]['readOnly'] === true)
                return true;
        }

        return false;
    }

    /**
     * Chrome_Registry::_isset()
     *
     * Determines whether the registry entry is set
     *
     * @param mixed $namespace
     *        namespace of the entry
     * @param mixed $key
     *        key of the entry
     * @return bool true: entry isset
     */
    public function _isset($namespace, $key)
    {
        return (isset($this->_registry[$namespace][$key]));
    }

    /**
     * Chrome_Registry::_unset()
     *
     * Unsets a registry entry
     * if entry is protected, then it throws a \Chrome\Exception
     *
     * @throws \Chrome\Exception
     * @param mixed $namespace
     *        namespace of the entry
     * @param mixed $key
     *        key of the entry
     * @return void
     */
    public function _unset($namespace, $key)
    {
        if($this->_isset($namespace, $key) and $this->getProtected($namespace, $key) !== true)
        {
            unset($this->_registry[$namespace][$key]);
        } elseif($this->getProtected($namespace, $key) === true)
        {
            throw new \Chrome\Exception('Cannot unset a protected value in Chrome_Registry::_unset()!');
        }
    }
}