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
 * @subpackage Chrome.Controller
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.03.2013 21:00:19] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

class Chrome_Controller_Factory
{
    protected $_requestHandler = null;

    public function __construct(Chrome_Request_Handler_Interface $requestHandler)
    {
        $this->_requestHandler = $requestHandler;
    }

    public function build($controllerClass)
    {
        if(!class_exists($controllerClass, false)) {
            throw new Chrome_Exception('Cannot initialize controller "'.$controllerClass.'" if class is not loaded!');
        }

        $controller = new $controllerClass($this->_requestHandler);

        return $controller;
    }

    public function loadControllerClass($file)
    {
    	if( _isFile( BASEDIR . $file ) ) {
		  require_once BASEDIR . $file;
		} else {

			try {
				import( $this->_class );
			}
			catch ( Chrome_Exception $e ) {
				throw new Chrome_Exception( 'No file found and could no find the corresponding file!', 2003 );
			}
			Chrome_Log::log( 'class "' . $this->_class .
				'" were found by autoloader! But it should inserted into db to speed up website!', E_NOTICE );
		}
    }
}