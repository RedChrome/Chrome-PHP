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
 * @package    CHROME-PHP
 * @subpackage Chrome.Cache
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [24.03.2013 11:31:17] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
interface Chrome_Cache_Interface
{
    /**
     * Creates a new cache object using given options
     *
     * @param Chrome_Cache_Option_Interface $options additional options for cache adapter
     * @return Chrome_Cache_Interface
     */
    public function __construct(Chrome_Cache_Option_Interface $options);

    /**
     * Sets a cache entry
     *
     * @param string $key
     * @param mixed $data
     * @return boolean true on success
     */
    public function set($key, $data);

    /**
     * Returns the data for the $key
     *
     * @return mixed null on failure
     */
    public function get($key);

    /**
     * Determines whether the cache entry with the name $key exists
     *
     * @return boolean, true if entry exists
     */
    public function has($key);

    /**
     * Removes an entry from cache. If $key does not exist, nothing happens
     *
     * @param string $key
     * @return void
     */
    public function remove($key);

    /**
     * Flushes the cache
     *
     * @return boolean
     */
     public function flush();

    /**
     * Deletes the whole cache
     *
     * @return bool
     */
    public function clear();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache.Option
 */
interface Chrome_Cache_Option_Interface
{

}

/**
 * Chrome_Cache_Factory
 *
 * @package     CHROME-PHP
 * @subpackage  Chrome.Cache
 */
class Chrome_Cache_Factory
{
    /**
     * Force to cache
     *
     * @var bool
     */
    private $_forceCaching = false;

    /**
     * Chrome_Cache_Factory::getInstance()
     *
     * Get instance
     *
     * @return Chrome_Cache_Factory
     */
    public static function getInstance()
    {
        return new self();
    }

    /**
     * Create a new cache
     *
     * @param string $cacheAdapter cache adapter, just suffix of Chrome_Cache_*
     * @param Chrome_Cache_Option_Interface $options options for adapter
     * @return Chrome_Cache_Abstract
     */
    public function factory($cacheAdapter, Chrome_Cache_Option_Interface $options)
    {
        // use a null object
        if(CHROME_ENABLE_CACHING === false AND $this->_forceCaching === false) {
            $cacheAdapter = 'null';
        }

        // naming conventions
        $cacheAdapter = ucfirst(strtolower($cacheAdapter));

        $this->_forceCaching = false;

        return $this->_createNewFactory($cacheAdapter, $options);
    }

    /**
     * Chrome_Cache_Factory::forceCaching()
     *
     * Force to cache, even if CHROME_ENABLE_CACHING is set to false
     *
     * @return Chrome_Cache_Factory
     */
    public function forceCaching()
    {
        $this->_forceCaching = true;
        return $this;
    }

    /**
     * Chrome_Cache_Factory::_createNewFactory()
     *
     * @param string $factory Factory
     * @param Chrome_Cache_Option_Interface $options Options, to pass to factory
     * @return Chrome_Cache_Abstract
     */
    private function _createNewFactory($factory, $options)
    {
        // need call_user_func_array, because we pass an array as arguments, not an array as an argument!

        $class =  'Chrome_Cache_'.$factory;

        return new $class($options);

        switch(count($options)) {
            case 0:
                return new $class();
            case 1:
                return new $class(array_pop($args));
            case 2:
                return new $class(array_pop($args), array_pop($args));
            case 3:
                return new $class(array_pop($args), array_pop($args), array_pop($args));
            default: {
                throw new Chrome_Exception('Well this code gets deleted, we are going to use an option class instead');
            }
        }
    }
}