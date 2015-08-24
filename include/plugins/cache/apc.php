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
 * @subpackage Chrome.Cache
 */

namespace Chrome\Cache\Option;

/**
 * An option interface for the session cache
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
interface Apc_Interface extends Option_Interface
{
    /**
     * Sets the namespace for apc. must be non-empty
     *
     * @param string $namespace
     */
    public function setNamespace($namespace);

    /**
     * @return string
     */
    public function getNamespace();

    /**
     * Sets how long the cache entries will live
     *
     * @param int $seconds (0 => infinity)
     */
    public function setTimeToLive($seconds);

    /**
     * @return int
     */
    public function getTimeToLive();
}

/**
 * Default implementation of the option session interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Apc implements Session_Interface
{
    protected $_namespace = '';

    protected $_ttl = 0;

    public function __construct(\Chrome\Request\Session_Interface $session, $namespace)
    {
        $this->setSession($session);
        $this->setNamespace($namespace);
    }

    public function setNamespace($namespace)
    {
        if(!is_string($namespace) and !empty($namespace))
        {
            throw new \Chrome\InvalidArgumentException('Argument $namespace must be a non-empty string');
        }

        $this->_namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->_namespace;
    }

    public function setTimeToLive($time)
    {
        if($time < 0 || !is_int($time)) {
            throw new \Chrome\InvalidArgumentException('Argument $time must be a non-negative integer');
        }

        $this->_ttl = $time;
    }

    public function getTimeToLive()
    {
        return $this->_ttl;
    }
}

namespace Chrome\Cache;

/**
 * A cache using the APC module
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Apc implements Cache_Interface
{
    protected $_namespace = null;

    protected $_timetolive = 0;

    public function __construct(\Chrome\Cache\Option\Apc_Interface $option)
    {
        $this->_namespace = $option->getNamespace();
        $this->_timetolive = $option->getTimeToLive();
    }

    public function clear()
    {
        $iterator = new \APCIterator('user', '#^namespace:.*#i');

        foreach($iterator as $key => $value) {
            \apc_delete($key);
        }
    }

    public function set($key, $data)
    {
        \apc_store($this->_namespace.':'.$key, $data, $this->_timetolive);
    }

    public function get($key)
    {
        $success = false;

        $value = \apc_fetch($this->_namespace.':'.$key, $success);

        return ($success) ? $value : null;
    }

    public function flush()
    {
        // do nothing, every cache entry is saved instantly
    }

    public function has($key)
    {
        return \apc_exists($this->_namespace.':'.$key);
    }

    public function remove($key)
    {
        \apc_delete($this->_namespace.':'.$key);
    }
}
