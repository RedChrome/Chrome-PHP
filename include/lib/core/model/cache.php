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
 * @subpackage Chrome.Model
 */

/**
 * @package    CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class Chrome_Model_Cache_Abstract extends Chrome_Model_Decorator_Abstract
{
    /**
     * Namespace for registry
     *
     * @var string
     */
    const CHROME_MODEL_CACHE_REGISTRY_NAMESPACE = 'Chrome_Model_Cache';

    /**
     * Default instance of the Chrome_Cache_Factory class
     *
     * @var Chrome_Cache_Factory_Interface
     */
    private static $_defaultCacheFactory = null;

    /**
     * instance of the Chrome_Cache_Factory class
     *
     * @var Chrome_Cache_Factory_Interface
     */
    protected $_cacheFactory = null;

    /**
     * contains an instance of a cache class
     *
     * @var Chrome_Cache_Interface
     */
    protected $_cache = null;

    /**
     * Name of the cache interface
     *
     * @var string
     */
    protected $_cacheInterface = '';

    /**
     * Options for the cache
     *
     * @var Chrome_Cache_Option_Interface
     */
    protected $_cacheOption = null;

    /**
     * Creates a new cache model
     *
     * This is a decorator pattern. To cache a model you can use this class.
     *
     * @param Chrome_Model_Abstract $instance instance of another model object
     * @return Chrome_Model_Cache_Abstract
     */
    public function __construct(Chrome_Model_Abstract $instance)
    {
        if(self::$_defaultCacheFactory === null) {
            self::$_defaultCacheFactory = new Chrome_Cache_Factory();
        }

        $this->_cacheFactory = self::$_defaultCacheFactory;

        parent::__construct($instance);
        $this->_setUpCache();
        $this->_createCache();
    }

    /**
     * This method is used to set up a new cache object.
     * Set here your $_cacheInterface and $_cacheOptions.
     *
     * @return void
     */
    abstract protected function _setUpCache();

    /**
     * Sets a cache object
     *
     * @param Chrome_Cache_Interface $cache
     */
    public function setCache(Chrome_Cache_Interface $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * Actually creates the cache
     *
     * @return void
     */
    protected function _createCache()
    {
        $this->_cache = $this->_cacheFactory->build($this->_cacheInterface, $this->_cacheOption);
    }

    /**
     * This methods clears the entire cache
     *
     * @return bool
     */
    public function clearCache()
    {
        if($this->_cache !== null) {
            $this->_cache->clear();
        }
    }
}
