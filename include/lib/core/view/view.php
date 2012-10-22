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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [21.10.2012 23:51:51] --> $
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
	 * @deprecated
	 */
	public function renderInit();

	/**
	 * @deprecated
	 */
	public function renderShutdown();

    /**
     * Returns the class name
     *
     * @return string
     */
    public function getClassName();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.View
 */
abstract class Chrome_View_Abstract implements Chrome_View_Interface
{
    /**
     * Contains the controller
     *
     * @var Chrome_Controller_Abstract
     */
	protected $_controller = null;

    /**
     * Cache for getClassName()
     *
     * @var string
     */
	protected $_className = null;

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
	 *@deprecated
	 */
	final protected function _preConstruct()
	{
		throw new Chrome_Exception( 'deprecated' );
	}

	/**
	 *@deprecated
	 */
	final protected function _postConstruct()
	{
		throw new Chrome_Exception( 'deprecated' );
	}

    /**
     *@deprecated
     */
	final public function renderInit()
	{

	}

    /**
     *@deprecated
     */
	final public function renderShutdown()
	{

	}

    /**
     * Renders the view
     *
     * @return mixed
     */
	public function render( Chrome_Controller_Interface $controller )
	{
		Chrome_Design::getInstance()->render( $this->_controller );
	}

    /**
     * magic method
     *
     * Calls a method from view helper if it exists
     *
     * @return mixed
     */
	public function __call( $func, $args )
	{
		if( $this->_isPluginMethod( $func ) ) {
			return $this->_callPluginMethod( $func, $args );
		} else {
			throw new Chrome_Exception( 'Cannot call method ' . $func . ' with args (' . var_export( $args, true ) .
				') in Chrome_View_Abstract::__call()!' );
		}
	}

    /**
     * Checks whether the method __call tries to run, exists in view helper
     *
     * @return boolean
     */
	protected function _isPluginMethod( $func )
	{
		return Chrome_View_Handler::getInstance()->isCallable( $func );
	}

    /**
     * Calls the method $func with arguments $args
     *
     * @return mixed
     */
	protected function _callPluginMethod( $func, $args )
	{
		return Chrome_View_Handler::getInstance()->call( $func, array_merge( array( $this ), $args ) );
	}

    /**
     * returns the class name of this class
     *
     * @return mixed
     */
	public function getClassName()
	{

		if( $this->_className === null ) {
			$this->_className = get_class( $this );
		}

		return $this->_className;
	}
}
