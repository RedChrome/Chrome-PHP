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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [05.03.2013 19:32:37] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Plugin_Interface
{
    /**
     * Chrome_View_Helper_Abstract::getMethods()
     *
     * Returns an array of all methods that this helper supply
     *
     * @return array
     */
     public function getMethods();

    /**
     * Chrome_View_Helper_Abstract::getClassName()
     *
     * Returns the class name of this heler (for better performance)
     *
     * @return string
     */
     public function getClassName();
}

/**
 * Chrome_View_Helper_Abstract
 *
 * Parent class for all view helpers
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
abstract class Chrome_View_Plugin_Abstract implements Chrome_View_Plugin_Interface
{
    /**
     * Chrome_View_Helper_Abstract::__construct()
     *
     * Registers a new helper
     *
     * @return Chrome_View_Helper_Abstract
     */
    public function __construct()
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
    protected function registerHelper()
    {
        // register the helper
        Chrome_View::getPluginObject()->registerPlugin($this);
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Plugin_Facade_Interface
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
     * registerPlugin()
     *
     * Registers a plugin
     *
     * @param Chrome_View_Helper_Abstract $helper
     * @return void
     */
    public function registerPlugin(Chrome_View_Plugin_Interface $plugin);
}

/**
 * Chrome_View_Handler
 *
 * Helper for Chrome_View
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Chrome_View_Plugin_Facade implements Chrome_View_Plugin_Facade_Interface
{
    /**
     * Contains all helpers
     * Structure:
     *  'className' => $plugin
     *
     * @var array
     */
    private $_plugins = array();

    /**
     * Contains all functions
     * Structure:
     *  'function' => 'className'
     *
     * @var array
     */
    private $_functions = array();

    /**
     * Registers a plugin
     *
     * @param Chrome_View_Plugin_Interface $plugin
     * @return void
     */
    public function registerPlugin(Chrome_View_Plugin_Interface $plugin)
    {
        // get class name
        $name = $plugin->getClassName();

        // helper already added?
        if(isset($this->_plugins[$name])) {
            return;
        }

        // add helper
        $this->_plugins[$name] = $plugin;

        // add all functions
        foreach($plugin->getMethods() AS $value) {
            $this->_functions[$value] = $name;
        }
    }

    /**
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
            throw new Chrome_Exception('Cannot call function '.$function.'. Function is not defined!');
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
        return call_user_func_array(array($this->_plugins[$this->_functions[$function]], $function), $arguments);
    }
}