<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 15:47:09] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Design_Model
 *
 * Model that handles the access point to the data resource for all designs
 *
*
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Model extends Chrome_Model_Abstract
{
    /**
     * Contains instance of this class
     *
     * @var Chrome_Design_Model
     */
    private static $_instance = null;

    /**
     * Chrome_Design_Model::__construct()
     *
     * @return Chrome_Design_Model
     */
    protected function __construct()
    {
    }

    /**
     * Chrome_Design_Model::getInstance()
     *
     * Singleton pattern
     *
     * @return Chrome_Design_Model
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Design_Model::__call()
     *
     * Modified version of Chrome_Model_Abstract
     *
     * @param string $func Name of the function
     * @param array $args Array of the arguments
     * @return
     */
    public function __call($func, $args)
    {
        if($this->_decorator === null) {
            // if no decorator is set, then we use the default model AS decorator
            // modified version of Chrome_Model_Abstract
            $this->_decorator = Chrome_Design_Model_Default::getInstance();
        }

        // do the same AS in parent::__call()
        return call_user_func_array(array($this->_decorator, $func), $args);
    }

}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Model_Default extends Chrome_Model_Abstract
{
    /**
     * @var Chrome_Design_Model_Default
     */
    private static $_instance = null;

    /**
     * Chrome_Design_Model_Default::__construct()
     *
     * @return Chrome_Design_Model_Default
     */
    protected function __construct()
    {
        // use a cache model between this model AND the db model
        $this->_decorator = new Chrome_Design_Model_Default_Cache(new Chrome_Design_Model_Default_DB());
    }

    /**
     * Chrome_Design_Model_Default::getInstance()
     *
     * @return Chrome_Design_Model_Default
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Model_Default_Cache extends Chrome_Model_Cache_Abstract
{
    /**
     * Cache file
     *
     * @var string
     */
    const CHROME_DESIGN_MODEL_DEFAULT_CACHE_CACHE_FILE = 'tmp/cache/_design.cache';

    /**
     * Chrome_Design_Model_Default_Cache::_cache()
     *
     * Creates the cache object
     *
     * @return Chrome_Cache_Abstract
     */
    protected function _cache()
    {
        // use serialization cache
        $this->_cache = parent::$_cacheFactory->factory('serialization', self::CHROME_DESIGN_MODEL_DEFAULT_CACHE_CACHE_FILE);
    }

    /**
     * Chrome_Design_Model_Default_Cache::getViews()
     *
     * Returns all views which are necessary for the design, fetched from cache
     *
     * @return array
     */
    public function getViews()
    {
        if(($cache = $this->_cache->load('view')) === null) {
            // cache miss

            $cache = $this->_decorator->getViews();
            $this->_cache->save('view', $cache);
        }

        return $cache;
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Model_Default_DB extends Chrome_Model_DB_Abstract
{
    /**
     * Chrome_Design_Model_Default_DB::__construct()
     *
     * Connect to database
     *
     * @return Chrome_Design_Model_Default_DB
     */
    public function __construct()
    {
        $this->_connect();
    }

    /**
     * Chrome_Design_Model_Default_DB::getViews()
     *
     * Returns all necessary views for design, fetched from db
     *
     * @return array
     */
    public function getViews()
    {

        $this->_dbInterfaceInstance->select('*')->from('design')->orderBy(array('position', 'order'), 'ASC')->execute();

        $views = array();

        foreach($this->_dbInterfaceInstance AS $result) {
            $views[$result['position']][] = $result;
        }

        $this->_dbInterfaceInstance->clear();

        return $views;
    }
}