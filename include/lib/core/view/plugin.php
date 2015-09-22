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
 * @subpackage Chrome.View
 */

namespace Chrome\View\Plugin;

/**
 * Interface for a view plugin.
 *
 * Every plugin provides a set of methods, which are accessible for every \Chrome\View\View_Interface object.
 * Those methods are published by the getMethods function.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Plugin_Interface
{
    /**
     * Returns an array of all methods that are provided by this plugin.
     *
     * @return array
     */
    public function getMethods();
}

/**
 * Parent class for all view plugins
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
abstract class AbstractPlugin implements Plugin_Interface
{
    protected $_applicationContext = null;

    public function __construct(\Chrome\Context\Application_Interface $appContext)
    {
        $this->_applicationContext = $appContext;
    }
}

/**
 * Interface for handling plugin calls
 *
 * The facade can register plugins, which will be called if a plugin method was accessed.
 * The mapping is done in the call-Function.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Facade_Interface
{
    /**
     * Checks whether a function is callable
     *
     * @param string $function
     * @return bool
     */
    public function isCallable($function);

    /**
     * Calls a function
     *
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    public function call($function, array $arguments);

    /**
     * Registers a plugin to the facade.
     *
     * This is necessary to publish methods from a Plugin_Interface to the plugin facade.
     *
     * After registering the plugin, one is now able to use the call function with these methods.
     *
     * Note: In case of conflicts of methods (different plugins provide methods with the same name), then the last added
     * plugin overwrites the plugin definition from the previous plugin.
     *
     * @param Chrome_View_Helper_Abstract $helper
     * @return void
     */
    public function registerPlugin(Plugin_Interface $plugin);
}

/**
 * Implementation of Facade_Interface
 *
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
class Facade implements Facade_Interface
{
    /**
     * Contains all helpers
     * Structure:
     * 'className' => $plugin
     *
     * @var array
     */
    private $_plugins = array();

    /**
     * Contains all functions
     * Structure:
     * 'function' => 'className'
     *
     * @var array
     */
    private $_functions = array();

    /**
     * Registers a plugin
     *
     * @param Plugin_Interface $plugin
     * @return void
     */
    public function registerPlugin(Plugin_Interface $plugin)
    {
        // get class name
        $name = get_class($plugin);

        // helper already added?
        if(isset($this->_plugins[$name]))
        {
            return;
        }

        // add helper
        $this->_plugins[$name] = $plugin;

        // add all functions
        foreach($plugin->getMethods() as $value)
        {
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
        if(!$this->isCallable($function))
        {
            throw new \Chrome\Exception('Cannot call function ' . $function . '. Function is not defined!');
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