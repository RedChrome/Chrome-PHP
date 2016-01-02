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
interface Memcache_Interface extends Option_Interface
{
    /**
     * Sets the namespace for memcache. must be non-empty
     *
     * @param string $namespace
     */
    public function setNamespace($namespace);

    /**
     * @return string
     */
    public function getNamespace();

    public function setAdapter(\Chrome\Database\Adapter\Memcache_Interface $memcache);

    /**
     * @return \Chrome\Database\Adapter\Memcache_Interface
     */
    public function getAdapter();

    public function setFlag($flag);

    public function getFlag();


    /**
     * @link{http://php.net/manual/de/memcache.set.php}
     *
     * $expire === 0: Never expire
     * $expire === unix timestamp: Expire at this timestamp
     * $expire <= 2592000 (30 days): Expire in $expire seconds
     *
     * @param int $expire
     */
    public function setExpire($expire);

    public function getExpire();
}

/**
 * Default implementation of the option memcache interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Memcache implements Memcache_Interface
{
    protected $_namespace = '';

    protected $_adapter = null;

    protected $_flag = 0;

    protected $_expire = 0;

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

    public function setAdapter(\Chrome\Database\Adapter\Memcache_Interface $memcache)
    {
        $this->_adapter = $memcache;
    }

    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function setFlag($flag)
    {
        $this->_flag = $flag;
    }

    public function getFlag()
    {
        return $this->_flag;
    }

    public function setExpire($expire)
    {
        $this->_expire = $expire;
    }

    public function getExpire()
    {
        return $this->_expire;
    }
}

namespace Chrome\Cache;

/**
 * A cache using the APC module
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Memcache implements Cache_Interface
{
    protected $_namespace = null;

    /**
     * @var \Chrome\Database\Adapter\Memcache_Interface
     */
    protected $_adapter = null;

    protected $_flag = 0;

    protected $_expire = 0;
    public function __construct(\Chrome\Cache\Option\Memcache_Interface $option)
    {
        $this->_namespace = $option->getNamespace();
        $this->_adapter = $option->getAdapter();
        $this->_flag = $option->getFlag();
        $this->_expire = $option->getExpire();

        if($this->_adapter === null)
        {
            throw new \Chrome\InvalidArgumentException('The adapter should not be null');
        }
    }

    public function clear()
    {
        $this->_adapter->clear();
    }

    public function set($key, $data)
    {
        $this->_adapter->set($this->_namespace.':'.$key, $data, $this->_flag, $this->_expire);
    }

    public function get($key)
    {
        return $this->_adapter->get($this->_namespace.':'.$key);
    }

    public function flush()
    {
        // do nothing, every cache entry is saved instantly
    }

    public function has($key)
    {
        return $this->_adapter->has($this->_namespace.':'.$key);
    }

    public function remove($key)
    {
        $this->_adapter->delete($this->_namespace.':'.$key);
    }
}
