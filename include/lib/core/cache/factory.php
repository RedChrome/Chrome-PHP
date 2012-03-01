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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2011 23:40:13] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
abstract class Chrome_Cache_Abstract implements Chrome_Cache_Interface
{
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
interface Chrome_Cache_Interface
{
    /**
     * factory()
     *
     * Creates a new cache instance
     *
     * @param string $file
     * @return
     */
    public static function factory($file);

    /**
     * clear()
     *
     * Deletes the cache
     *
     * @return bool
     */
    public function clear();
}

/**
 * Chrome_Cache_Factory
 *
 * @package     CHROME-PHP
 * @subpackage  Chrome.Cache
 * @todo remove _getFilesInDir, use _isFile instead!
 */
class Chrome_Cache_Factory
{
    /**
     * Instance of this class
     *
     * @var Chrome_Cache_Factory
     */
    private static $_instance;

    /**
     * Contains available cache factories
     *
     * @var array
     */
    private $_factories = array();

    /**
     * Default cache fatory
     *
     * @var string
     */
    private $_defaultFactory = 'File';
    
    /**
     * Force to cache
     * 
     * @var bool
     */ 
    private $_forceCaching = false;
    
    /**
     * Path where all cache factories are saved
     *
     * @var string
     */
    const CHROME_CACHE_FACTORY_PATH_TO_CACHE_FACTORIES = 'include/plugins/Cache/';

    /**
     * Chrome_Cache_Factory::__construct()
     *
     * @return Chrome_Cache_Factory
     */
    private function __construct()
    {
        $this->_getFactories();
    }

    /**
     * Chrome_Cache_Factory::_getFactories()
     *
     * Get all cache factories from dir
     *
     * @return void
     */
    private function _getFactories()
    {
        // check wheter the dir exists
        if(!_isDir(self::CHROME_CACHE_FACTORY_PATH_TO_CACHE_FACTORIES)) {
            throw new Chrome_Exception('Path '.self::CHROME_CACHE_FACTORY_PATH_TO_CACHE_FACTORIES.' does not exist! Cannot load cache factories in Chrome_Cache_Factory::_getFactories()!');
        }

        // get the files
        $this->_factories = _getFilesInDir(self::CHROME_CACHE_FACTORY_PATH_TO_CACHE_FACTORIES);
    }

    /**
     * Chrome_Cache_Factory::getInstance()
     *
     * Get instance
     *
     * @return Chrome_Cache_Factory
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Chrome_Cache_Factory::factory()
     *
     * Create a new cache
     * Any additional argument gets passed to factory() of the specific cache factory class
     *
     * @param string $factory Factory
     * @param mixed $args pass any arguments to factory
     * @return Chrome_Cache_Abstract
     */
    public function factory($factory = null)
    {
        if($factory === null OR $factory === false) {
            $factory = $this->_defaultFactory;
        }
        
        // use a null object
        if(CHROME_ENABLE_CACHING === false AND $this->_forceCaching == false) {
            $factory = 'null';
        }

        // get args AND shift first one
        $args = func_get_args();
        array_shift($args);

        // check whether factory exists
        if(!in_array($factory.'.php', $this->_factories)) {
            throw new Chrome_Exception('Cannot load cache factory '.$factory.'! File does not exist in Chrome_Cache_Factory::factory()!');
        }

        require_once self::CHROME_CACHE_FACTORY_PATH_TO_CACHE_FACTORIES.$factory.'.php';
        
        $this->_forceCaching = false;
        
        return $this->_createNewFactory($factory, $args);
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
     * @param array $args Arguments, to pass to factory
     * @return Chrome_Cache_Abstract
     */
    private function _createNewFactory($factory, $args)
    {
        return call_user_func_array(array('Chrome_Cache_'.$factory, 'factory'), $args);
        #return call_user_func_array(array(new ReflectionClass('Chrome_Cache_'.$factory), 'newInstance'), $args);
    }
}