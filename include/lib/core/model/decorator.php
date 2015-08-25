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

namespace Chrome\Model;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class AbstractDecorator extends AbstractModel
{
    protected $_decorable = null;

    public function __construct(Model_Interface $instance = null)
    {
        if($instance !== null) {
            $this->setDecorable($instance);
        }
    }

    public function __call($func, $args)
    {
        return call_user_func_array(array($this->_decorable, $func), $args);
    }

    public function setDecorable(\Chrome\Model\Model_Interface $instance)
    {
        $this->_decorable = $instance;
    }

    public function getDecorable()
    {
        return $this->_decorable;
    }
}

/**
 * An abstract class to cache a model class
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Model
 */
abstract class AbstractCache extends AbstractDecorator
{
    /**
     * contains an instance of a cache class
     *
     * @var \Chrome\Cache\Cache_Interface
     */
    protected $_cache = null;

    /**
     * Creates a new cache model
     *
     * This is a decorator pattern.
     *
     * @param \Chrome\Model\Model_Interface $instance instance of another model object
     */
    public function __construct(\Chrome\Model\Model_Interface $instance, \Chrome\Cache\Cache_Interface $cache)
    {
        parent::__construct($instance);
        $this->setCache($cache);
    }

    /**
     * Sets a cache object
     *
     * @param Chrome_Cache_Interface $cache
     */
    public function setCache(\Chrome\Cache\Cache_Interface $cache)
    {
        $this->_cache = $cache;
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

