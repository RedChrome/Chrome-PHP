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
 * @subpackage Chrome.Router
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.09.2012 13:42:25] --> $
 * @author     Alexander Book
 */
if( CHROME_PHP !== true ) die();

/**
 * @see core/error/exception/router.php
 */
require_once LIB . 'exception/router.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Chrome_Router_Interface
{
	const CHROME_ROUTER_REGISTRY_NAMESPACE = 'Chrome_Router';

	public function route( Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data );

	public function match( Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data );

	public function getResource();

	public function url( $name, array $options );

	public function addRouterClass( Chrome_Router_Route_Interface $obj );
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Chrome_Router_Route_Interface
{
	public function match( Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data );

	public function getResource();

	public function url( $name, array $options );
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
interface Chrome_Router_Resource_Interface
{
	public function setFile( $file );

	public function getFile();

	public function setClass( $class );

	public function getClass();

	public function initClass(Chrome_Request_Handler_Interface $requestHandler);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Router_Resource implements Chrome_Router_Resource_Interface
{
	protected $_file = null;

	protected $_class = null;

	public function __construct()
	{
	}

	public function setFile( $file )
	{
		$this->_file = $file;
	}

	public function getFile()
	{
		return $this->_file;
	}

	public function setClass( $class )
	{
		$this->_class = $class;
	}

	public function getClass()
	{
		return $this->_class;
	}

	public function initClass(Chrome_Request_Handler_Interface $requestHandler)
	{

		if( $this->_class == '' or empty( $this->_class ) ) {
			throw new Chrome_Exception( 'No Class set in Chrome_Router_Resource!', 2002 );
		}

		if( !class_exists( $this->_class, false ) ) {

			$file = $this->_file;

			if( $file != '' and _isFile( BASEDIR . $file ) ) {
				require_once BASEDIR . $file;
			} else {

				try {
					import( $this->_class );
				}
				catch ( Chrome_Exceptopm $e ) {
					throw new Chrome_Exception( 'No file found and could no find the corresponding file!', 2003 );
				}
				Chrome_Log::log( 'class "' . $this->_class .
					'" were found by autoloader! But it should inserted into db to speed up website!', E_NOTICE );
			}
		}

		return new $this->_class($requestHandler);
	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Router implements Chrome_Router_Interface, Chrome_Exception_Processable_Interface
{
	private static $_instance = null;

	protected $_routeInstance = null;

	protected $_routerClasses = array();

	protected $_resource = null;

	protected $_exceptionHandler = null;

	private function __construct()
	{
	}

	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function match( Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data )
	{
		try {
			foreach( $this->_routerClasses as $router ) {
				if( $router->match( $url, $data ) === true ) {
					$this->_resource = $router->getResource();

					break;
				}
			}

			if( $this->_resource == null or !( $this->_resource instanceof Chrome_Router_Resource_Interface ) ) {
				throw new Chrome_Exception( 'Could not found adequate controller class!', 2001 );
			}

		}
		catch ( Chrome_Exception $e ) {
			$this->_exceptionHandler->exception( $e );
		}
	}

	public function route( Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data )
	{
		// replace ROOT,
		$path = ltrim( preg_replace( '#\A' . ROOT_URL . '#', '', '/' . $url->getPath() ), '/' );
		$url->setPath( $path );

		try {

			$this->match( $url, $data );

		}
		catch ( Chrome_Exception $e ) {
			$this->_exceptionHandler->exception( $e );
		}

		return $this->_resource;
	}

	public function getResource()
	{
		return $this->_resource;
	}

	public function addRouterClass( Chrome_Router_Route_Interface $obj )
	{
		$this->_routerClasses[] = $obj;
	}

	public function setExceptionHandler( Chrome_Exception_Handler_Interface $obj )
	{
		$this->_exceptionHandler = $obj;

		return $this;
	}

	public function getExceptionHandler()
	{
		return $this->_exceptionHandler;
	}

	public function url( $name, array $options )
	{
		foreach( $this->_routerClasses as $router ) {
			if( ( $return = $router->url( $name, $options ) ) !== false ) {
				return $return;
			}
		}
	}
}
