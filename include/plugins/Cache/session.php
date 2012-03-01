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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [23.10.2011 19:31:07] --> $
 */
 
/**
 *
 * Null Object for caching
 * 
 * @package CHROME-PHP
 * @subpackag Chrome.Cache
 * @deprecated
 */  
class Chrome_Cache_Session extends Chrome_Cache_Abstract
{
    private static $_instance = null;
    
    protected $_namespace = null;
    
    protected function __construct($namespace) {
        $this->_namespace = $namespace;
    }
    
    public static function factory($namespace) {
        return new self($namespace);
    }
    
    public function clear() {
        $session = Chrome_Session::getInstance();
        
        $session->set($this->_namespace, null);
    }
    
    public function save($key, $data) {
        $session = Chrome_Session::getInstance();
        
        $session->set($this->_namespace, array($key => $data) );
    }
    
    public function load($key) {
        $session = Chrome_Session::getInstance();
        
        $cache = $session->get($this->_namespace);
        
        return (isset($cache[$key])) ? $cache[$key] : null;
    }
}