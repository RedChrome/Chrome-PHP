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
 * @subpackage Chrome.Config
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [18.11.2012 14:24:38] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Config
 */
class Chrome_Model_Config extends Chrome_Model_Decorator_Abstract
{
    private static $_instance = null;

    protected function __construct()
    {
        $this->_decorator = new Chrome_Model_Config_Cache(new Chrome_Model_Config_DB());
    }

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
 * @subpackage Chrome.Config
 */
class Chrome_Model_Config_DB extends Chrome_Model_Database_Abstract
{
    public function __construct()
    {
        $this->_connect();
    }

    public function loadConfig()
    {
        $this->_dbInterfaceInstance
            ->select(
                array(
                 'name',
                 'subclass',
                 'value',
                )
            )
            ->from('config')
            ->execute();

        $config = array();

        foreach($this->_dbInterfaceInstance as $result) {
            // sets $config[subclass][name] = value
            $config[$result['subclass']][$result['name']] = $result['value'];
        }

        $this->_dbInterfaceInstance->clear();

        return $config;
    }

    public function setConfig($name, $subclass, $value, $type, $modul = '')
    {
        $this->_dbInterfaceInstance
            ->insert()
            ->into('config')
            ->values(
                array(
                 $name,
                 $subclass,
                 $value,
                 $type,
                 $modul,
                )
            )
            ->execute();

        $this->_dbInterfaceInstance->clear();
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Config
 */
class Chrome_Model_Config_Cache extends Chrome_Model_Cache_Abstract
{
    const CHROME_MODEL_CONFIG_CACHE_CACHE_FILE = 'tmp/cache/_config.cache';

    protected function _cache()
    {
        $this->_cache = parent::$_cacheFactory->factory('serialization', self::CHROME_MODEL_CONFIG_CACHE_CACHE_FILE);
    }

    public function loadConfig()
    {
        if(($cache = $this->_cache->load('config')) === null) {
            // cache miss

            $cache = $this->_decorator->loadConfig();
            $this->_cache->save('config', $cache);
        }

        return $cache;
    }

    public function setConfig($name, $subclass, $value, $type, $modul = '')
    {
        $this->_cache->remove('config');
        $this->_decorator->setConfig($name, $subclass, $value, $type, $modul);
    }
}