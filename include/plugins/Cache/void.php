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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2011 19:25:23] --> $
 */
 
/**
 *
 * Null Object for caching
 * 
 * @package CHROME-PHP
 * @subpackag Chrome.Cache
 * @deprecated
 */  
class Chrome_Cache_Void extends Chrome_Cache_Abstract
{
    private static $_instance = null;
    
    public function __call($method, $params) {
        return null;
    }
    
    public function __get($key) {
        return null;
    }
    
    public function __set($key, $value) {
        return null;
    }
    
    public static function factory($file) {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function clear() {
        return null;
    }
}