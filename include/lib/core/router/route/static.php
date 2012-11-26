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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [26.11.2012 10:12:05] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Route_Static implements Chrome_Router_Route_Interface
{
	protected $_resource = null;
	protected $_model = null;

	public function __construct( Chrome_Model_Abstract $model )
	{
		$this->_model = $model;
		Chrome_Router::getInstance()->addRouterClass( $this );
		try {
			Chrome_Registry::getInstance()->set( Chrome_Router_Interface::CHROME_ROUTER_REGISTRY_NAMESPACE,
				'Chrome_Route_Static', $this, false );
		}
		catch ( Chrome_Exception $e ) {
			unset( $e );
			// do nothing
		}
	}

	public function match( Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data )
	{

		$row = $this->_model->getRoute( $url->getPath() );

		if( $row == false ) {
			return false;
		} else {

			$this->_resource = new Chrome_Router_Resource();

			$this->_resource->setFile( $row['file'] );
			$this->_resource->setClass( $row['class'] );

			if( count( $row['GET'] ) > 0 ) {
				$data->setGET( $row['GET'] );
			}
			if( count( $row['POST'] ) > 0 ) {
				$data->setPOST( $row['POST'] );
			}
			return true;
		}
	}

	public function getResource()
	{
		return $this->_resource;
	}

	public function url( Chrome_Router_Resource_Interface $resource )
	{

	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Model_Route_Static extends Chrome_Model_Decorator_Abstract
{
	private static $_instance = null;

	protected function __construct()
	{
		$this->_decorator = new Chrome_Model_Route_Static_Cache( new Chrome_Model_Route_Static_DB() );
	}

	public static function getInstance()
	{
		if( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Model_Route_Static_Cache extends Chrome_Model_Cache_Abstract
{
	const CHROME_MODEL_ROUTE_STATIC_CACHE_CACHE_FILE = 'tmp/cache/router/_static.cache';

	protected function _cache()
	{
		$this->_cache = parent::$_cacheFactory->factory( 'serialization', self::CHROME_MODEL_ROUTE_STATIC_CACHE_CACHE_FILE );
	}

	public function getRoute( $search )
	{

		if( ( $return = $this->_cache->load( 'getRoute_' . $search ) ) === null ) {

			$return = $this->_decorator->getRoute( $search );

			if( $return !== false ) {
				$this->_cache->save( 'getRoute_' . $search, $return );
			}
		}

		return $return;
	}

	public function findRoute( $search )
	{

		if( ( $return = $this->_cache->load( 'findRoute_' . $search ) ) === null ) {

			$return = $this->_decorator->findRoute( $search );

			if( $return !== false ) {
				$this->_cache->save( 'findRoute_' . $search, $return );
			}
		}

		return $return;
	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Model_Route_Static_DB extends Chrome_Model_Database_Abstract
{
	protected $_dbInterface = 'model';

	public function __construct()
	{
		parent::__construct();
	}

	public function getRoute( $search )
	{

        $result = $this->_dbInterfaceInstance->prepare('routeStaticGetRoute')
            ->execute(array($search));


		$row = $result->getNext();

		$this->_dbInterfaceInstance->clear();

        if($row === false) {
            return false;
        }


		// translate key=value,key2=value2 into an array {key => value, key2=>value2}
		$GET = array();
		if( !empty( $row['GET'] ) ) {

			// input is like key=value,key2=value2,..
			$keyValuePairs = explode( ',', $row['GET'] );
			foreach( $keyValuePairs as $keyValuePair ) {

				$keyValue = explode( '=', $keyValuePair );
				$GET[$keyValue[0]] = $keyValue[1];
			}
		}
		$row['GET'] = $GET;


		$POST = array();
		if( !empty( $row['POST'] ) ) {

			// input is like key=value,key2=value2,..
			$keyValuePairs = explode( ',', $row['POST'] );
			foreach( $keyValuePairs as $keyValuePair ) {

				$keyValue = explode( '=', $keyValuePair );
				$POST[$keyValue[0]] = $keyValue[1];
			}
		}
		$row['POST'] = $POST;

		return $row;
	}

	public function findRoute( $name )
	{

        $result = $this->_dbInterfaceInstance->prepare('routeStaticFindRoute')
            ->execute(array($name));

        $row = $result->getNext();

		$this->_dbInterfaceInstance->clear();

		return $row;
	}
}
