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
 * @deprecated
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.08.2011 17:50:53] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
interface Chrome_Design_Registry_Interface extends Chrome_Registry_Abstract_Interface
{
    /**
     * add()
     * 
     * Adds a Chrome_View object to the registry
     * 
     * @param Chrome_View_Interface $obj a Chrome_View object
     * @return void
     */
    public function add(Chrome_View_Interface $obj);

    /**
     * set()
     * 
     * Unsets all Chrome_View objects AND then add the objects from the array
     * 
     * @param array $viewObjects array of Chrome_View_Interface objects
     * @return void
     */
    public function set(array $viewObjects);
    
    /**
     * _unset()
     * 
     * Unsets a Chrome_View object, which class name is $vieClass
     * 
     * @param string $viewClass Name of the Chrome_View object
     * @return bool
     */
    public function _unset($viewClass);

    /**
     * _isset()
     * 
     * Determines wheter the Chrome_View object is set OR not
     * 
     * @param string $viewClass Name of the Chrome_View object
     * @return bool
     */
    public function _isset($viewClass);

    /**
     * get()
     * 
     * Returns a Chrome_View object
     * 
     * @param string $viewClass Name of the Chrome_View object
     * @return Chrome_View_Interface
     */
    public function get($viewClass);
    
    /**
     * getAll()
     * 
     * Returns all objects in Registry
     * 
     * @return array
     */
    public function getAll();
}

/**
 * Chrome_Design_Registry
 * 
 * Registry class for Chrome_View_Interface objects
 * 
*
 * @package CHROME-PHP
 * @subpackage Chrome.Design
 */
class Chrome_Design_Registry extends Chrome_Registry_Abstract implements Chrome_Design_Registry_Interface
{
    /**
     * Namespace for registry
     */ 
    const CHROME_DESIGN_REGISTRY_NAMESPACE = 'DESIGN';
    
    /**
     * Contains instance of this class
     * 
     * @var Chrome_Design_Registry
     */ 
    private static $_instance = null;

    /**
     * Chrome_Design_Registry::__construct()
     * 
     * @return Chrome_Design_Registry
     */
    protected function __construct() {
        
    }
    
    /**
     * Chrome_Design_Registry::getInstance()
     * 
     * Singleton pattern
     * 
     * @return Chrome_Design_Registry
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_Design_Registry::add()
     * 
     * Adds a Chrome_View object to the registry
     * 
     * @param Chrome_View_Interface $obj a Chrome_View object
     * @return void
     */
    public function add(Chrome_View_Interface $obj)
    {
        // add the object
        $this->_registry[self::CHROME_DESIGN_REGISTRY_NAMESPACE][$obj->getPosition()][] = array('value' => $obj, 'readOnly' => true, 'class' => get_class($obj));
    }

    /**
     * Chrome_Design_Registry::set()
     * 
     * Unsets all Chrome_View objects AND then add the objects from the array
     * 
     * @param array $viewObjects array of Chrome_View_Interface objects
     * @return void
     */
    public function set(array $viewObjects)
    {
        $this->_registry[self::CHROME_DESIGN_REGISTRY_NAMESPACE] = array();
        
        foreach($viewObjects AS $obj) {
            $this->add($obj);
        }
    }

    /**
     * Chrome_Design_Registry::get()
     * 
     * Returns a Chrome_View object
     * 
     * @param string $viewClass Name of the Chrome_View object
     * @return Chrome_View_Interface
     */
    public function get($viewClass)
    {
        foreach($this->_registry[self::CHROME_DESIGN_REGISTRY_NAMESPACE] AS $position) {
            foreach($position AS $view) {
                if($view['class'] === $viewClass) {
                    return $view['value'];
                }
            }
        }
    }
    
    /**
     * Chrome_Design_Registry::_unset()
     * 
     * Unsets a Chrome_View object, which class name is $vieClass
     * 
     * @param string $viewClass Name of the Chrome_View object
     * @return bool
     */
    public function _unset($viewClass)
    {
        foreach($this->_registry[self::CHROME_DESIGN_REGISTRY_NAMESPACE] AS $type => $position) {
            foreach($position AS $key => $view) {
                if($view['class'] === $viewClass) {
                    unset($this->_registry[self::CHROME_DESIGN_REGISTRY_NAMESPACE][$type][$key]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Chrome_Design_Registry::_isset()
     * 
     * Determines wheter the Chrome_View object is set OR not
     * 
     * @param string $viewClass Name of the Chrome_View object
     * @return bool
     */
    public function _isset($viewClass)
    {
        foreach($this->_registry[self::CHROME_DESIGN_REGISTRY_NAMESPACE] AS $position) {
            foreach($position AS $view) {
                if($view['class'] === $viewClass) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Chrome_Design_Registry::getAll()
     * 
     * Returns all objects in Registry
     * 
     * @return array
     */
    public function getAll()
    {
        $array = array();
        foreach($this->_registry[self::CHROME_DESIGN_REGISTRY_NAMESPACE] AS $key => $position) {
            foreach($position AS $view) {
                $array[$key][] = $view['value'];
            }
        }
        
        return $array;
    }
}