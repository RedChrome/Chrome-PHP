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
 * @subpackage Chrome.Registry
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.01beta <!-- phpDesigner :: Timestamp [15.09.2011 23:28:18] --> $
 */
if(CHROME_PHP !== true)
    die();

/**
 * Interface for Chrome_Registr_Singleton
 * 
 * @package CHROME-PHP
 * @subpackage Chrome.Registry
 */ 
interface Chrome_Registry_Singleton_Interface extends Chrome_Registry_Abstract_Interface
{
    /**
     * Namespace of all singleton instances
     *
     * @var string
     */
    const CHROME_REGISTRY_SINGLETON_NAMESPACE = 'SINGLETON';

    /**
     * Chrome_Registry_Singleton::set()
     *
     * Sets an instance to Chrome_Registry
     * Returns boolean true on success
     *
     * @param string $class Name of the class
     * @param object $instance Instance of the class
     * @throws Chrome_Exception if $instance is not an object, OR $class is already set
     * @return bool
     */
    public function set($class, $instance);
    
    /**
     * Chrome_Registry_Singleton::get()
     *
     * Gets an instance of Chrome_Registry
     * Returns an instance of a class, saved before by Chrome_Registry_Singleton::set()
     *
     * @param string $class Name of the class
     * @return object OR null
     */
    public function get($class);
    
    /**
     * Chrome_Registry_Singleton::_isset()
     *
     * Checks whether the $class is already set
     * Returns boolean true if set, false else
     *
     * @param string $class Name of the class
     * @return boolean
     */
    public function _isset($class);
    
    /**
     * Chrome_Registry_Singleton::_unset()
     *
     * Cannot unset a singleton class!
     * Always throws Chrome_Exception
     * 
     * @throws Chrome_Exception
     * @param string $class [ignored]
     * @return void
     */
    public function _unset($class);
}

/**
 * Chrome_Registry_Singleton
 *
 * Registry class to access singletons saved in registry
 *
 * @package		CHROME-PHP
 * @subpackage  Chrome.Registry
 * @author		Alexander Book
 * @copyright	2009 by Alexander Book
 * @version		2009
 * @access		public
 */
class Chrome_Registry_Singleton extends Chrome_Registry_Abstract implements Chrome_Registry_Singleton_Interface
{
    /**
     * Contains instance of Chrome_Registry_Singleton (Singleton pattern)
     *
     * @var Chrome_Registry_Singleton
     */
    private static $_instance;

    /**
     * Chrome_Registry_Singleton::getInstance()
     *
     * Singleton Pattern
     *
     * @return Chrome_Registry_Singleton
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Chrome_Registry_Singleton::set()
     *
     * Sets an instance to Chrome_Registry
     * Returns boolean true on success
     *
     * @param string $class Name of the class
     * @param object $instance Instance of the class
     * @throws Chrome_Exception if $instance is not an object, OR $class is already set
     * @return bool
     */
    public function set($class, $instance)
    {
        if(!is_object($instance)) {
            throw new Chrome_Exception('Cannot set class! $instance is not an object in Chrome_Registry_Singleton::set()!');
        }

        if($this->_isset($class)) {
            throw new Chrome_Exception('Cannot reset class '.$class.'! Use reset() instead!');
        }

        $this->_registry[self::CHROME_REGISTRY_SINGLETON_NAMESPACE][$class] = array('value' => $instance, 'readOnly' => true);

        return true;
    }

    /**
     * Chrome_Registry_Singleton::get()
     *
     * Gets an instance of Chrome_Registry
     * Returns an instance of a class, saved before by Chrome_Registry_Singleton::set()
     *
     * @param string $class Name of the class
     * @return object OR null
     */
    public function get($class)
    {
        if(isset($this->_registry[$namespace][$key]))
            return $this->_registry[$namespace][$key]['value'];
        else
            return null;
    }

    /**
     * Chrome_Registry_Singleton::_isset()
     *
     * Checks whether the $class is already set
     * Returns boolean true if set, false else
     *
     * @param string $class Name of the class
     * @return boolean
     */
    public function _isset($class)
    {
        return (isset($this->_registry[$namespace][$key]));
    }

    /**
     * Chrome_Registry_Singleton::_unset()
     *
     * Cannot unset a singleton class!
     * Always throws Chrome_Exception
     *
     * @param string $class [ignored]
     * @return void
     */
    public function _unset($class)
    {
        throw new Chrome_Exception('Cannot unset a class!');
    }
}