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
 * @package CHROME-PHP
 * @subpackage Chrome.Config
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.04.2013 15:05:55] --> $
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Config
 */
class Chrome_Model_Config_DB extends Chrome_Model_Database_Abstract
{
    const TYPE_INT = 'integer', TYPE_STRING = 'string', TYPE_DOUBLE = 'double', TYPE_BOOL = 'boolean';

    protected function _setDatabaseOptions()
    {
        $this->_dbInterface = 'model';

        $this->_dbResult = 'iterator';
    }

    public function loadConfig()
    {
        $db = $this->_getDBInterface();

        $result = $db->loadQuery('configLoadConfiguration')->execute();
        $config = array();
        foreach($result as $item)
        {
            // sets $config[subclass][name] = value
            $config[$item['subclass']][$item['name']] = $this->_convertTypes($item['type'], $item['value']);
        }

        return $config;
    }

    protected function _convertTypes($type, $value)
    {
        switch($type)
        {
            case self::TYPE_INT:
                return (integer) $value;
            case self::TYPE_STRING:
                return (string) $value;
            case self::TYPE_DOUBLE:
                return (double) $value;
            case self::TYPE_BOOL:
                return (boolean) $value;
            default:
                return $value;
        }
    }

    public function setConfig($name, $subclass, $value, $type = null, $modul = '', $hidden = 0)
    {
        if($type === null)
        {
            $type = gettype($value);
        }

        $db = $this->_getDBInterface();

        $db->loadQuery('configSetConfiguration')->execute(array($name, $subclass, $value, $type, $modul, $hidden));
    }
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Config
 */
class Chrome_Model_Config_Cache extends Chrome_Model_Cache_Abstract
{

    protected function _setUpCache()
    {
        $this->_cacheOption = new Chrome_Cache_Option_Serialization();
        $this->_cacheOption->setCacheFile(CACHE . '_config.cache');
        $this->_cacheInterface = 'serialization';
    }

    public function loadConfig()
    {
        if(!$this->_cache->has('config'))
        {
            // cache miss

            $cache = $this->_decorable->loadConfig();
            $this->_cache->set('config', $cache);
        } else {
            $cache = $this->_cache->get('config');
        }

        return $cache;
    }

    public function setConfig($name, $subclass, $value, $type = null, $modul = '', $hidden = 0)
    {
        $this->_cache->remove('config');
        $this->_decorable->setConfig($name, $subclass, $value, $type, $modul, $hidden);
    }
}