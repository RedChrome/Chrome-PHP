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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2013 16:25:36] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
interface Chrome_View_Interface extends Chrome_Design_Renderable
{
    /**
     * @todo rename method
     */
    #public function style(Chrome_Design_Style_Interface $style);

    /**
     * Sets a var
     *
     * @param string $key
     * @param mixed $value
     */
    public function setVar($key, $value);

    /**
     * Gets a set var
     *
     * @return mixed $value
     */
    public function getVar($key);

    /**
     * Sets the plugin object for every view
     * The Plugin object is used to dynamically add functionality to all views.
     * The Views can the access the functions using:
     *  $this->additionalPluginFunction($args);
     * at which additionalPluginFunction is any function given by the plugin object
     *
     * @param Chrome_View_Handler_Interface $plugin
     * @return void
     */
    public static function setPluginObject(Chrome_View_Handler_Interface $object);

    /**
     * Returns the plugin object
     *
     * @return Chrome_View_Handler_Interface
     */
    public static function getPluginObject();
}

abstract class Chrome_View implements Chrome_View_Interface
{
    /**
     * Contains data for plugin methods
     *
     * @var array
     */
    protected $_vars          = array();

    /**
     * Plugin object
     *
     * @var Chrome_View_Handler
     */
    protected static $_plugin = null;

    /**
     * magic method
     *
     * Calls a method from view helper if it exists
     *
     * @return mixed
     */
	public function __call( $func, $args )
	{
		//if( $this->_isPluginMethod( $func ) ) {
			return $this->_callPluginMethod( $func, $args );
		//} else {
		//	throw new Chrome_Exception( 'Cannot call method ' . $func . ' with args (' . var_export( $args, true ) .
		//		') in Chrome_View_Abstract::__call()!' );
		//}
	}

    /**
     * Checks whether the method __call tries to run, exists in view helper
     *
     * @return boolean
     */
	protected function _isPluginMethod( $func )
	{
		return self::$_plugin->isCallable( $func );
	}

    /**
     * Calls the method $func with arguments $args
     *
     * @return mixed
     */
	protected function _callPluginMethod( $func, $args )
	{
		return self::$_plugin->call( $func, array_merge( array( $this ), $args ) );
	}

    public function setVar($key, $value) {
        $this->_vars[$key] = $value;
    }

    public function getVar($key) {
        return (isset($this->_vars[$key])) ? $this->_vars[$key] : null;
    }

    public static function setPluginObject(Chrome_View_Handler_Interface $plugin) {
        self::$_plugin = $plugin;
    }

    public static function getPluginObject() {
        return self::$_plugin;
    }
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
abstract class Chrome_View_Abstract extends Chrome_View
{
    /**
     * Contains the controller
     *
     * @var Chrome_Controller_Abstract
     */
	protected $_controller    = null;

    /**
     * Constructor
     *
     * @return Chrome_View_Abstract
     */
	public function __construct( Chrome_Controller_Abstract $controller )
	{
		$this->_controller = $controller;
	}

    /**
     * Renders the view
     *
     * @return mixed
     */
	public function render( Chrome_Controller_Interface $controller )
	{
	   //TODO: remove this method (just in this class)
	}
}

abstract class Chrome_View_Strategy_Abstract extends Chrome_View_Abstract
{
    protected $_views = null;

    public function render( Chrome_Controller_Interface $controller )
    {
        $return = '';

        if(!is_array($this->_views)) {
            $this->_views = array($this->_views);
        }

        foreach($this->_views as $view) {
            $return .= $view->render($controller);
        }

        return $return;
    }
}
