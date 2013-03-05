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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 17:34:13] --> $
 * @author     Alexander Book
 */

if( CHROME_PHP !== true ) die();
//TODO: finish this class
//TODO: use Chrome_Request_Data_Interface $data
/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Route_Administration implements Chrome_Router_Route_Interface
{
	protected $_GET = array();

	protected $_resource = null;

	protected $_model = null;

	public function __construct( Chrome_Model_Abstract $model )
	{
		$this->_model = $model;
	}


    // TODO: check whether user is allowed to access...
	public function match( Chrome_URI_Interface $url, Chrome_Request_Data_Interface $data )
	{
		$path = $url->getPath();

		$dirs = explode( '/', $path, 3 );

		if( strtolower( $dirs[0] ) != 'admin' ) {
			return false;
		}

		// go to admin overview
		if( count( $dirs ) == 1 or ( $dirs[1] ) == '' and count( $dirs ) == 2 ) {

            if(!_isAllowed(new Chrome_Authorisation_Resource($this->_model->getDefaultResourceID(), ''))) {
                return false;
            }

			$this->_resource = new Chrome_Router_Resource_Administration();
			$this->_resource->setFile( $this->_model->getDefaultResourceFile() );
			$this->_resource->setClass( $this->_model->getDefaultResourceClass() );

			return true;
		}

        if(!isset($dirs[2]) OR empty($dirs[2])) {
            $dirs[2] = '';
        }

		// lookup whether the request resource exists
        $result = $this->_model->getClassAndFile($dirs[1].'_'.$dirs[2]);

        if(!_isAllowed(new Chrome_Authorisation_Resource($result['resource_id'], $result['resource_transformation']))) {
            return false;
        }



        if($result == null) {
            return false;
        }

        if(empty($result['file']) OR empty($result['class'])) {
            Chrome_Log::log('Error in Route_Administration. The db returned obviosly wrong values: '.var_export($result, true), E_ERROR);
            throw new Chrome_Exception('Could not route to administration! Either the requested file or class is empty');
        }

        $this->_resource = new Chrome_Router_Resource_Administration();
        $this->_resource->setFile($result['file']);
        $this->_resource->setClass($result['class']);


        return true;
	}


	public function getResource()
	{
		return $this->_resource;
	}

	public function url( Chrome_Router_Resource_Interface $resource )
	{
		die( 'Not implemented yet' );
	}
}

class Chrome_Router_Resource_Administration extends Chrome_Router_Resource
{

	public function initClass( Chrome_Request_Handler_Interface $requestHandler )
	{

		if( empty( $this->_class ) ) {
			throw new Chrome_Exception( 'Cannot instantiate a class with an empty class name!' );
		}

        throw new Chrome_Exception('Administration access is not finished');
	}

}

class Chrome_Model_Route_Administration extends Chrome_Model_Abstract
{
    public function __construct() {

    }

    public function getDefaultResourceFile() {

    }

    public function getDefaultResourceClass() {
        return 'Chrome_Controller_Admin_Index';
    }

    public function getDefaultResourceID() {
        return 'admin_index';
    }

}

class Chrome_Model_Route_Administration_DB extends Chrome_Model_Database_Abstract
{
	public function getClassAndFile( $name )
	{
		$dbInstance = $this->getDBInterface();

		$dbInstance->select( array( 'class', 'file', 'access' ) )->from( 'route_adminstration' )->where( 'name = "' .
			$dbInstance->escape( $name ) . '"' )->limit( 0, 1 )->execute();

		$result = $dbInstance->next();


        return $result;
	}
}
