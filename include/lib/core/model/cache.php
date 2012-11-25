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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.11.2012 23:49:45] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

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
     * Instance of the Chrome_Cache_Factory class
     *
     * @var Chrome_Cache_Factory
     */
    protected static $_cacheFactory = null;


    /**
     * contains an instance of a cache class
     *
     * @var Chrome_Cache_Abstract
     */
    protected $_cache = null;

    /**
     * Chrome_Model_Cache_Abstract::__construct()
     *
     * Creates a new cache model
     *
     * This is a decorator pattern. To cache a model you can use this class.
     *
     * @param Chrome_Model_Abstract $instance instance of another model object
     * @return Chrome_Model_Cache_Abstract
     */
    public function __construct(Chrome_Model_Abstract $instance)
    {
        if(self::$_cacheFactory === null) {
            self::$_cacheFactory = Chrome_Cache_Factory::getInstance();
        }

        parent::__construct($instance);
        $this->_cache();
    }

    /**
     * Chrome_Model_Cache_Abstract::_cache()
     *
     * This method is used to e.g create a new cache object
     *
     * @return void
     */
    abstract protected function _cache();

    /**
     * Chrome_Model_Cache_Abstract::clearCache()
     *
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
