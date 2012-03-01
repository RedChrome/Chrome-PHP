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
 * @subpackage Chrome.Cache
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2011 23:42:37] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */ 
class Chrome_Cache_File extends Chrome_Cache_Abstract
{
    private $_cacheInstance = null;

    private $_defaultFrontendOptions = array('liftetime' => CHROME_CACHE_LIFETIME, 'automatic_serialization' => false,
        'cache_id_prefix' => CHROME_CACHE_PREFIX, 'caching' => true, 'write_control' => false);

    private $_defaultBackendOptions = array('file_locking' => false);

    public static function factory($file, $frontendOptions = array(), $backendOptions = array())
    {
        return new self($file, $frontendOptions, $backendOptions);
    }

    public function __construct($file, $frontendOptions = array(), $backendOptions = array())
    {
        if (!is_array($frontendOptions)) {
            $frontendOptions = $this->_defaultFrontendOptions;
        } else {
            $frontendOptions = array_merge($this->_defaultFrontendOptions, $frontendOptions);
        }

        if (!is_array($backendOptions)) {
            $backendOptions = $this->_defaultBackendOptions;
        } else {
            $backendOptions = array_merge($this->_defaultBackendOptions, $backendOptions);
        }

        $backendOptions['cache_dir'] = $file;

        $_cacheInstance = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
    }

    public function __call($function, $args)
    {
        return call_user_func_array(array($this->_cacheInstance, $function), $args);
    }

    public function clear()
    {
        $this->_cacheInstance->clean(Zend_Cache::CLEANING_MODE_ALL);
    }
}