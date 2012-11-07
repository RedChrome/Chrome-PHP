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
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Request
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [07.11.2012 21:57:55] --> $
 * @author     Alexander Book
 */

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
interface Chrome_Request_Interface
{
	/**
     * Singleton pattern
     *
	 * @return Chrome_Request_Interface
	 */
	public static function getInstance();


	/**
     * Returns the request handler
     *
	 * @return Chrome_Request_Handler_Interface
	 */
	public function getRequest();

	/**
     * Adds a request handler. They determine which request is sent
     *
	 * @param Chrome_Request_Handler_Interface $obj
	 * @return void
	 */
	public function addRequestObject( Chrome_Request_Handler_Interface $obj );

	/**
     * Returns the request data object
     *
	 * @retur Chrome_Request_Data_Interface
	 */
	public function getRequestDataObject();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
interface Chrome_Request_Handler_Interface
{
	/**
     * Determines whether this class can handle the sent request
     *
	 * @return boolean
	 */
	public function canHandleRequest();

	/**
     * Returns the request data object
     *
	 * @return Chrome_Request_Data_Interface
	 */
	public function getRequestData();
}

/**
 * This class saves the required global data $_SERVER, $_GET, etc.. which is for
 * the specific request needed. E.g. Ajax request cant send a file
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
interface Chrome_Request_Data_Interface
{
	/**
     * Singleton pattern
     *
	 * @return Chrome_Request_Data_Interface
	 */
	public static function getInstance();

    /**
     * Returns all data. GET,POST,SERVER,FILES, etc..
     *
     * @return array
     */
	public function getData();

	public function getGET( $key = null );

	public function getPOST( $key = null );

	public function getSERVER( $key = null );

	public function getFILES( $key = null );

	public function getCOOKIE( $key = null );

	public function getSESSION( $key = null );

	public function getREQUEST( $key = null );

	public function getENV( $key = null );

	public function setGET( array $array );

	public function setPOST( array $array );

	public function setFILES( array $array );

	public function setENV( array $array );

	public function setSERVER( array $array );
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
abstract class Chrome_Request_Data_Abstract implements Chrome_Request_Data_Interface
{

    /**
     * @var array
     */
	protected $_vars = array();

    /**
     * @return Chrome_Request_Data_Interface
     */
	protected function __construct()
	{
		$this->_vars = array(
			'SERVER' => $_SERVER,
			'GET' => $_GET,
			'POST' => $_POST,
			'FILES' => $_FILES,
			'COOKIE' => $_COOKIE,
			'SESSION' => $_SESSION,
			'REQUEST' => $_REQUEST,
			'ENV' => $_ENV );
	}

	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function getGET( $key = null )
	{

		if( $key === null ) {
			return $this->_vars['GET'];
		} else
			if( isset( $this->_vars['GET'][$key] ) ) {
				return $this->_vars['GET'][$key];
			}

	}

	public function getPOST( $key = null )
	{
		if( $key === null ) {
			return $this->_vars['POST'];
		} else
			if( isset( $this->_vars['POST'][$key] ) ) {
				return $this->_vars['POST'][$key];
			}
	}

	public function getSERVER( $key = null )
	{
		if( $key === null ) {
			return $this->_vars['SERVER'];
		} else
			if( isset( $this->_vars['SERVER'][$key] ) ) {
				return $this->_vars['SERVER'][$key];
			}
	}

	public function getFILES( $key = null )
	{
		if( $key === null ) {
			return $this->_vars['FILES'];
		} else
			if( isset( $this->_vars['FILES'][$key] ) ) {
				return $this->_vars['FILES'][$key];
			}
	}

	public function getCOOKIE( $key = null )
	{
		if( $key === null ) {
			return $this->_vars['COOKIE'];
		} else
			if( isset( $this->_vars['COOKIE'][$key] ) ) {
				return $this->_vars['COOKIE'][$key];
			}
	}

	public function getSESSION( $key = null )
	{
		if( $key === null ) {
			return $this->_vars['SESSION'];
		} else
			if( isset( $this->_vars['SESSION'][$key] ) ) {
				return $this->_vars['SESSION'][$key];
			}
	}

	public function getREQUEST( $key = null )
	{
		if( $key === null ) {
			return $this->_vars['REQUEST'];
		} else
			if( isset( $this->_vars['REQUEST'][$key] ) ) {
				return $this->_vars['REQUEST'][$key];
			}
	}

	public function getENV( $key = null )
	{
		if( $key === null ) {
			return $this->_vars['ENV'];
		} else
			if( isset( $this->_vars['ENV'][$key] ) ) {
				return $this->_vars['ENV'][$key];
			}
	}

	public function getData()
	{
		return $this->_vars;
	}

	public function setGET( array $array )
	{
		$this->_vars['GET'] = array_merge($this->_vars['GET'], $array);
	}

	public function setPOST(  array $array )
	{
		$this->_vars['POST'] = array_merge($this->_vars['POST'], $array);
	}

	public function setFILES(  array $array )
	{
		$this->_vars['FILES'] = array_merge($this->_vars['FILES'], $array);
	}

	public function setENV(  array $array )
	{
		$this->_vars['ENV'] = array_merge($this->_vars['ENV'], $array);
	}

	public function setSERVER(  array $array )
	{
		$this->_vars['SERVER'] = array_merge($this->_vars['SERVER'], $array);
	}
}

/**
 * Facade for Request_Data obj, and Request_Handler obj
 *
 * This class checks every request handler, whether he can handle the current request and
 * returns the request data obj corresponding to the handler, which can handle the request.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
class Chrome_Request implements Chrome_Request_Interface
{
	/**
	 * @var Chrome_Request
	 */
	private static $_instance = null;

	/**
	 * The currently used request object
	 *
	 * @var Chrome_Request_Handler_Interface
	 */
	private $_request = null;

	/**
	 * A list of all request objects
	 *
	 * @var array
	 */
	private $_requests = array();

	/**
	 * @var Chrome_Request_Data_Interface
	 */
	private $_requestData = null;


	private function __construct()
	{

	}

    /**
     * @return Chrome_Request_Data_Interface
     */
	public function getRequestDataObject()
	{
		// the if is always true, well it should be...
		return ( $this->_requestData === null or $this->getRequest() != null ) ? $this->_requestData : $this->_requestData;
	}

    /**
     * @return Chrome_Request_Interface
     */
	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

    /**
     * Checks which handler is able to handle the request and returns this object
     *
     * @return Chrome_Request_Handler_Interface
     */
	public function getRequest()
	{
	    // cache
		if( $this->_request !== null ) {
			return $this->_request;
		}

		// figure out which class can handle the request
		// there must at least one obj which can handle..
		foreach( $this->_requests as $key => $requestObj ) {

			if( $requestObj->canHandleRequest() === true ) {
				$this->_request = $requestObj;
				$this->_requestData = $requestObj->getRequestData();

				break;
			}
		}

		// well that should never happen. every Chrome_Request_Handler_Interface has to return an object of Chrome_Request_Data_Interface
		// null is also not allowed!
		if( !( $this->_requestData instanceof Chrome_Request_Data_Interface ) OR $this->_requestData === null ) {
			throw new Chrome_Exception( 'Unexpected return value of "' . get_class( $this->_request ) .
				'" in method getRequestData()! Expected an object of interface Chrome_Request_Data_Interface, actual="' .
				get_class( $this->_requestData ) . '"! Violation of interface declaration!' );
		}

		// unset all global data, but DO NOT UNSET SESSION!!! http://php.net/manual/de/function.unset.php#77926
		unset( $_GET, $_POST, $_FILES, $_COOKIE, $_REQUEST, $_ENV, $_SERVER, $GLOBALS );
		$_SESSION = array();

		// now we dont need them any more
		unset( $this->_requests );

		return $this->_request;
	}

    /**
     * @param Chrome_Request_Handler_Interface $obj the handle you want to add to queue
     * @return void
     */
	public function addRequestObject( Chrome_Request_Handler_Interface $obj )
	{
		// only add a request, if we havent decided which request is sent...
		if( $this->_request === null ) {
			$this->_requests[] = $obj;
		}
	}
}
