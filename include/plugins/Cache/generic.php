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
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
namespace Chrome\Cache\Option;

/**
 * An options interface for a generic cache
 *
 * A generic cache needs to know which object provides the data, and another cache to cache this data appropriately
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
interface Generic_Interface extends Option_Interface
{
    /**
     * Returns an object which provides the data by accessing any method
     *
     * @return mixed
     */
    public function getDecoratetable();

    /**
     * Returns a cache which handles the actual caching
     *
     * @return Cache_Interface
     */
    public function getCache();
}

/**
 * A simple implementation of the option interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Generic implements Generic_Interface
{
    protected $_decoratetable = null;

    protected $_cache = null;

    public function __construct(\Chrome\Cache\Cache_Interface $cache, $decorateable)
    {
        if(!is_object($decorateable)) {
            throw new \Chrome_InvalidArgumentException('The argument $decoratable must be an object');
        }

        $this->_cache = $cache;
        $this->_decoratetable = $decorateable;
    }

    public function getDecoratetable()
    {
        return $this->_decoratetable;
    }

    public function getCache()
    {
        return $this->_cache;
    }
}

namespace Chrome\Cache;

/**
 * A simple generic cache.
 *
 * This class caches every method call of the decorateable by using another cache.
 *
 * For performance and api reasons, you shouldn't use this class. But for a quick cache feature, this is good enough.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Generic implements Cache_Interface
{
    protected $_options = null;

    protected $_decorator = null;

    public function __construct(\Chrome\Cache\Option\Generic_Interface $options)
    {
        $this->_options = $options;
        $this->_decorator = $options->getDecoratetable();
        $this->_cache = $options->getCache();
    }

    protected function _createUniqueKey($functionName, array $arguments)
    {
        return $functionName.'->'.implode('/', $arguments);
    }

    public function __call($functionName, $arguments)
    {
        if(!method_exists($this->_decorator, $functionName)) {
            throw new \Chrome_InvalidArgumentException('Decorateable does not support the method "'.$functionName.'"');
        }

        if(!is_array($arguments)) {
            $arguments = (array) $arguments;
        }

        $key = $this->_createUniqueKey($functionName, $arguments);

        if(!$this->has($key)) {
            $this->set($key, call_user_func_array(array($this->_decorator, $functionName), $arguments));
        }

        return $this->get($key);
    }

    public function set($key, $data)
    {
        $this->_cache->set($key, $data);
    }

    public function get($key)
    {
        return $this->_cache->get($key);
    }

    public function has($key)
    {
        return $this->_cache->has($key);
    }

    public function remove($key)
    {
        return $this->_cache->remove($key);
    }

    public function flush()
    {
        return $this->_cache->flush();
    }

    public function clear()
    {
        $this->_cache->clear();
    }
}