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
 * @subpackage Chrome.View
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 18:02:00] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Helper_Abstract_Interface
{
    /**
     * getInstance()
     *
     * @return Chrome_View_Helper_Abstract
     */
    public static function getInstance();
}

/**
 * Chrome_View_Helper_Abstract
 *
 * Parent class for all view helpers
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
abstract class Chrome_View_Helper_Abstract implements Chrome_View_Helper_Abstract_Interface
{
    /**
     * Is the helper already registered?
     *
     * @var bool
     */
    protected $_isRegisterd = false;

    /**
     * Chrome_View_Helper_Abstract::__construct()
     *
     * Registers a new helper
     *
     * @return Chrome_View_Helper_Abstract
     */
    protected function __construct()
    {
        $this->registerHelper();
    }

    /**
     * Chrome_View_Helper_Abstract::registerHelper()
     *
     * registers a helper
     *
     * @return void
     */
    final public function registerHelper()
    {
        // helper already registered?
        if($this->_isRegisterd === true) {
            return;
        }

        // register the helper
        Chrome_View_Handler::getInstance()->registerHelper($this);

        $this->_isRegisterd = true;
    }

    /**
     * Chrome_View_Helper_Abstract::getMethods()
     *
     * Returns an array of all methods that this helper supply
     *
     * @return array
     */
    abstract public function getMethods();

    /**
     * Chrome_View_Helper_Abstract::getClassName()
     *
     * Returns the class name of this heler (for better performance)
     *
     * @return string
     */
    abstract public function getClassName();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Handler_Interface
{
    /**
     * isCallable()
     *
     * Checks whether a function is callable
     *
     * @param string $function
     * @return bool
     */
    public function isCallable($function);

    /**
     * call()
     *
     * Calls a function
     *
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    public function call($function, array $arguments);

    /**
     * @see call()
     */
    public function __call($function, $arguments);

    /**
     * registerHelper()
     *
     * Registers a helper
     *
     * @param Chrome_View_Helper_Abstract $helper
     * @return void
     */
    public function registerHelper(Chrome_View_Helper_Abstract $helper);
}

/**
 * Chrome_View_Handler
 *
 * Helper for Chrome_View
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Chrome_View_Handler implements Chrome_View_Handler_Interface
{
    /**
     * Contains self instance
     *
     * @var Chrome_View_Handler
     */
    private static $_instance = null;

    /**
     * Contains all helpers
     * Structure:
     *  'className' => $helper
     *
     * @var array
     */
    private $_helpers = array();

    /**
     * Contains all functions
     * Structure:
     *  'function' => 'className'
     *
     * @var array
     */
    private $_functions = array();

    /**
     * Chrome_View_Handler::getInstance()
     *
     * @return Chrome_View_Handler
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Chrome_View_Handler::registerHelper()
     *
     * Registers a helper
     *
     * @param Chrome_View_Helper_Abstract $helper
     * @return void
     */
    public function registerHelper(Chrome_View_Helper_Abstract $helper)
    {
        // get class name
        $name = $helper->getClassName();

        // helper already added?
        if(isset($this->_helpers[$name])) {
            return;
        }

        // add helper
        $this->_helpers[$name] = $helper;

        // get functions of this helper
        $functions = $helper->getMethods();

        // add all functions
        foreach($functions AS $value) {
            $this->_functions[$value] = $name;
        }
    }

    /**
     * Chrome_View_Handler::__call()
     *
     * Wrapper for Chrome_View_Handler::call()
     *
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    public function __call($function, $arguments)
    {
        return $this->call($function, $arguments);
    }

    /**
     * Chrome_View_Handler::__callStatic()
     *
     * Wrapper for Chrome_View_Handler::call()
     *
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($function, $arguments)
    {
        return self::getInstance()->call($function, $arguments);
    }

    /**
     * Chrome_View_Handler::isCallable()
     *
     * Checks whether a function is callable
     *
     * @param string $function
     * @return bool
     */
    public function isCallable($function)
    {
        return (isset($this->_functions[$function]));
    }

    /**
     * Chrome_View_Handler::call()
     *
     * Calls a function
     *
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    public function call($function, array $arguments)
    {
        // check first whether the function is callable
        if(!$this->isCallable($function)) {
            //TODO: var_export will fail with "Nesting level too deep - recursive dependency". use other export.
            throw new Chrome_Exception('Cannot call function '.$function.' with arguments ('.var_export($arguments, true).')! Function is not defined in Chrome_View_Handler::call()!');
        }

        // call the function
        //
        // $helperClassName = $this->_functions[$function];
        //
        // $helper = $this->_helpers[$helperClassName];
        //
        // call_user_func_array(array($helper, $function), $arguments);
        //
        // Compact style:
        return call_user_func_array(array($this->_helpers[$this->_functions[$function]], $function), $arguments);
    }
}


/**
 * Chrome_View_Helper_View
 *
 * Example of a Helper
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View.Helper
 * @author Alexander Book
 * @deprecated
 */
class Chrome_View_Helper_Design_Registry extends Chrome_View_Helper_Abstract
{
    /**
     * @var Chrome_View_Helper_Design_Registry
     */
    private static $_instance = null;

    /**
     * Chrome_View_Helper_Design_Registry::getInstance()
     *
     * @return Chrome_View_Helper_View
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Chrome_View_Helper_Design_Registry::addView()
     *
     * Wrapper for Chrome_Design_Registry::getInstance()->add()
     *
     * @param Chrome_View_Abstract $obj
     * @param Chrome_View_Abstract $view
     * @return void
     */
    public function addView(Chrome_View_Abstract $obj, Chrome_View_Interface $view)
    {
        Chrome_Design_Registry::getInstance()->add($view);
    }

    /**
     * Chrome_View_Helper_Design_Registry::setViews()
     *
     * @param Chrome_View_Abstract $obj
     * @param array $views
     * @return void
     */
    public function setViews(Chrome_View_Abstract $obj, array $views)
    {
        Chrome_Design_Registry::getInstance()->set($views);
    }

    /**
     * Chrome_View_Helper_Design_Registry::getViews()
     *
     * @return array
     */
    public function getViews()
    {
        return Chrome_Design_Registry::getInstance()->getAll();
    }

    /**
     * Chrome_View_Helper_Design_Registry::getView()
     *
     * @param Chrome_View_Abstract $obj
     * @param Chrome_View_Abstract $viewClass
     * @return array
     */
    public function getView(Chrome_View_Abstract $obj, $viewClass)
    {
        return Chrome_Design_Registry::getInstance()->getView($viewClass);
    }

    /**
     * Chrome_View_Helper_Design_Registry::unsetView()
     *
     * @param miChrome_View_Abstractxed $obj
     * @param Chrome_View_Abstract $viewClass
     * @return void
     */
    public function unsetView(Chrome_View_Abstract $obj, $viewClass)
    {
        Chrome_Design_Registry::getInstance()->_unset($viewClass);
    }

    /**
     * Chrome_View_Helper_Design_Registry::isView()
     *
     * @param Chrome_View_Abstract $obj
     * @param Chrome_View_Abstract $viewClass
     * @return bool
     */
    public function isView(Chrome_View_Abstract $obj, $viewClass)
    {
        return Chrome_Design_Registry::getInstance()->_isset($viewClass);
    }

    /**
     * Chrome_View_Helper_Design_Registry::getMethods()
     *
     * returns all methods
     *
     * @return array
     */
    public function getMethods()
    {
        return array('addView', 'setViews', 'getViews', 'getView', 'removeView', 'isView');
    }

    /**
     * Chrome_View_Helper_Design_Registry::getClassName()
     *
     * returns class name
     *
     * @return string
     */
    public function getClassName()
    {
        return 'Chrome_View_Helper_Design_Registry';
    }
}

/**
 * Initialize this helper
 *  => functions are added to Chrome_Handler
 *      => Chrome_View can now use these functions
 */
Chrome_View_Helper_Design_Registry::getInstance();