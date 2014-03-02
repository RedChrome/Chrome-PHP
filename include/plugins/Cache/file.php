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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [07.03.2012 18:49:36] --> $
 */

// @todo clean up this class to use it again

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Cache
 */
class Chrome_Cache_File extends Chrome_Cache_Abstract
{
    private $_cacheInstance = null;

    private $_defaultFrontendOptions = array('liftetime' => CHROME_CACHE_LIFETIME, 'automatic_serialization' => false,
        'cache_id_prefix' => 'id_', 'caching' => true, 'write_control' => false);

    private $_defaultBackendOptions = array('file_locking' => false);

    public static function factory($file, $frontendOptions = array(), $backendOptions = array())
    {
        return new self($file, $frontendOptions, $backendOptions);
    }

    public function __construct($file, $frontendOptions = array(), $backendOptions = array())
    {
        require_once LIB.'Zend/Cache.php';

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

        $this->_cacheInstance = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
    }

    public function __call($function, $args)
    {
        var_dump( call_user_func_array(array($this->_cacheInstance, $function), $args) );
    }

    public function clear()
    {
        $this->_cacheInstance->clean(Zend_Cache::CLEANING_MODE_ALL);
    }
}