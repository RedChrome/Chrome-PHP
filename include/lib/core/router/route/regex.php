<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Router
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 14:42:30] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */ 
class Chrome_Route_Regex implements Chrome_Router_Route_Interface
{
    /**
     * How much information are allowed in an URL,
     * ^= class + action + GET params
     * @var int
     */
    const CHROME_ROUTE_REGEX_MAX_LEVEL = 20;
    
    private static $_instance = null;
    
    private $_resources = array();
    
    
    
    
    protected $_controllerClass = null;

   
    private function __construct() {
        Chrome_Router::getInstance()->addRouterClass($this);        
    }
    
    public static function getInstance() {

        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    public function match(Chrome_URI_Interface $url) {

        $array = explode('/', $url->getPath(), self::CHROME_ROUTE_REGEX_MAX_LEVEL);
        
        if(sizeof($array) <= 1) {
            return false;
        }
           
        $this->_resource->search($array);
        
        if($this->_resource->hasFound() === true) {
            // found resource   
         
            $this->_controllerClass = $this->_resources->getControllerClass();
         
            
        } else {
            return false;
        }
    }
        
    /*
	public static function _match($url, $data)
	{
		$matches = array();
		if(preg_match($data['route'],$url, $matches)) {
			self::_replaceWildcards($data, $matches);
			return $data;

		} else return false;
	}

	private static function _replaceWildcards(&$data, $matches) {
		foreach($data AS $key => $value) {
			if(is_array($value)) {
				self::_replaceWildcards($value, $matches);
				$data[$key] = $value;
			} else
				$data[$key] = preg_replace('#:([0-9]{1,})?#e', '$matches[\\1]', $value);
		}
	}*/

}