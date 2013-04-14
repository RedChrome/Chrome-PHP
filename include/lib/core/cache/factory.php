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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.04.2013 20:22:24] --> $
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
 * @package CHROME-PHP
 * @subpackage Chrome.Cache.Factory
 */
interface Chrome_Cache_Factory_Interface
{
    /**
     * Creates a new cache
     *
     * @param string $cacheAdapter cache adapter, just suffix of Chrome_Cache_*, see plugins/Cache for available caches
     * @param Chrome_Cache_Option_Interface $options options for adapter
     * @return Chrome_Cache_Abstract new cache object
     */
    public function build($cacheAdapter, Chrome_Cache_Option_Interface $options);
}

/**
 * Chrome_Cache_Factory
 *
 * @package     CHROME-PHP
 * @subpackage  Chrome.Cache.Factory
 */
class Chrome_Cache_Factory implements Chrome_Cache_Factory_Interface
{
    /**
     * Create a new cache
     *
     * @param string $cacheAdapter cache adapter, just suffix of Chrome_Cache_*
     * @param Chrome_Cache_Option_Interface $options options for adapter
     * @return Chrome_Cache_Abstract
     */
    public function build($cacheAdapter, Chrome_Cache_Option_Interface $options)
    {
        if($cacheAdapter === null OR empty($cacheAdapter)) {
            throw new Chrome_InvalidArgumentException('No valid cache adapter given!');
        }

        // naming conventions
        $cacheAdapter = ucfirst(strtolower($cacheAdapter));

        $class = 'Chrome_Cache_'.$cacheAdapter;

        return new $class($options);
    }
}