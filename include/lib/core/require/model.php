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
 * @subpackage Chrome.Require
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 12:01:57] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @todo: remove
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Model_Require extends Chrome_Model_Decorator_Abstract
{
    /**
     * Chrome_Model_Require::__construct()
     *
     * Constructor, sets decorator
     *
     * @return Chrome_Model_Require
     */
    public function __construct(Chrome_Model_Interface $model)
    {
        $this->_decorator = $model;
    }

    public function setClass($class, $file) {
        return $this->_decorator->setClass($class, $file);
    }

    public function getClasses() {
        return $this->_decorator->getClasses();
    }

    public function getClass($name) {
        return $this->_decorator->getClass($name);
    }
}

/**
 * Chrome_Model_Require_DB
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Model_Require_DB extends Chrome_Model_Database_Abstract
{
    /**
     * Chrome_Model_Require_DB::__construct()
     *
     * connects to database
     *
     * @return Chrome_Model_Require_DB
     */
    public function __construct()
    {
        $this->_dbComposition = new Chrome_Database_Composition('model', 'iterator');
    }

    /**
     * Chrome_Model_Require_DB::getRequirements()
     *
     * Gets Requirements
     *
     * @return array
     */
    public function getRequirements()
    {
        $require = array();

        $db = $this->_getDBInterface();

        $result = $db->prepare('requireGetRequirements')
            ->execute();

        // loop through every result
        foreach($result AS $value) {
            $require[] = $value;
        }

        return $require;
    }

    /**
     * Chrome_Model_Require_DB::getClasses()
     *
     * @return array
     */
    public function getClasses()
    {
        $_class = array();

        $db = $this->_getDBInterface();

        $result = $db->prepare('requireGetClasses')
            ->execute();

        // loop through
        foreach($result AS $value) {
            $_class[$value['name']] = $value['file'];
        }

        return $_class;
    }

    /**
     * Chrome_Model_Require_DB::addClass()
     *
     * @param string $name
     * @param string $file
     * @param bool $override
     * @return void
     */
    public function addClass($name, $file, $override = false)
    {
        $db = $this->_getDBInterface();

        // delete old entry
        if($override === true) {
            // make sql query AND clean up DB interface

            $db->prepare('requireDeleteEntryByName')
                ->execute(array($name));

        } else {

            // check whether there is already the same class defined
            $resultObj = $db->prepare('requireDoesNameExist')
                ->execute(array($name));

            if(!$resultObj->isEmpty()) {
                throw new Chrome_Exception('There is already a class ' . $name . ' defined in database! Override set to false in Chrome_Require::addClass()!');
            }

        }

        $db = $this->_getDBInterface();

         // insert the class to db
        $db->prepare('requireSetClass')
            ->execute(array($name, $file));
    }

    /**
     * Chrome_Model_Require_Cache::getClass
     *
     * Does nothing
     *
     * @param string $name name of the class
     * @return string
     */
    public function getClass($name) {
        return false;
    }

    /**
     * Chrome_Model_Require_Cache::setClass
     *
     * Does nothing
     *
     * @param string $name name of the class
     * @param string $file file to the corresponding class
     *
     */
    public function setClass($name, $file) {
        return false;
    }
}

/**
 * Chrome_Model_Require_Cache
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Model_Require_Cache extends Chrome_Model_Cache_Abstract
{
    /**
     * File where you want to save the cache file
     *
     * @var string
     */
    const CHROME_MODEL_REQUIRE_CACHE_CACHE_FILE = 'tmp/cache/_require.cache';

    /**
     * Namespace
     *
     * @var string
     */
     const CHROME_MODEL_REQUIRE_CACHE_CLASS_NAMESPACE = '_';

    /**
     * Chrome_Model_Require_Cache::_cache()
     *
     * Sets cache instance
     *
     * @return void
     */
    protected function _cache()
    {
        $this->_cache = parent::$_cacheFactory->forceCaching()->factory('serialization', self::CHROME_MODEL_REQUIRE_CACHE_CACHE_FILE);
    }

    /**
     * Chrome_Model_Require_Cache::getRequirements()
     *
     * Gets all requirements
     *
     * @return array
     */
    public function getRequirements()
    {
        if(($return = $this->_cache->load('getRequirements')) === null) {

            $return = $this->_decorator->getRequirements();
            $this->_cache->save('getRequirements', $return);
        }

        return $return;
    }

    /**
     * Chrome_Model_Require_Cache::getClasses()
     *
     * Gets all classes
     *
     * @return array
     */
    public function getClasses()
    {
        if(($return = $this->_cache->load('getClasses')) === null OR count($return) == 0) {

            $return = $this->_decorator->getClasses();

            $this->_cache->save('getClasses', $return);
        }

        return $return;
    }

    /**
     * Chrome_Model_Require_Cache::getClass
     *
     * Gets the file of a saved class
     *
     * @param string $name name of the class
     * @return string
     */
    public function getClass($name) {
        if(($return = $this->_cache->load(self::CHROME_MODEL_REQUIRE_CACHE_CLASS_NAMESPACE.$name)) !== null) {

            return $return;
        }

        return false;
    }

    /**
     * Chrome_Model_Require_Cache::setClass
     *
     * Saves the file for the class
     *
     * @param string $name name of the class
     * @param string $file file to the corresponding class
     *
     */
    public function setClass($name, $file) {
        $this->_cache->save(self::CHROME_MODEL_REQUIRE_CACHE_CLASS_NAMESPACE.$name, $file);
    }
}