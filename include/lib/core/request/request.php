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
 * @subpackage Chrome.Request
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.08.2011 14:45:58] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */ 
final class Chrome_Request
{
	/**
	 * Path where all Request files are placed
	 *
	 * @var string
	 */
	const CHROME_REQUEST_CLASS_PATH = 'core/request';

	/**
	 * Contains the used Request instance
	 *
	 * @var Chrome_Request_Abstract
	 */
	private static $_requestInstance = null;

    /**
     * Which request class gets used
     *
     * @var string
     */
    private static $_requestClass = 'HTTP';

    /**
     * Contains all created request instances
     *
     * @var array
     */
    private static $_requestInstances = array();

	/**
	 * Singleton
	 *
	 * @return Chrome_Request
	 */
	private function __construct() {}

	/**
	 * Singleton
	 *
	 * @return void
	 */
	private function __clone() {}

	/**
	 * Singleton
	 *
	 * @return Chrome_Request_Abstract object
	 */
	public static function getInstance()
	{
		if(self::$_requestInstance === null) {
			// get the Request object, create it
			self::$_requestInstance = self::_createRequestInstance();
		}
		return self::$_requestInstance;
	}

    private static function _createRequestInstance()
    {
        if(isset(self::$_requestInstances[self::$_requestClass])) {
            return self::$_requestInstances[self::$_requestClass];
        } else {

            self::_loadRequestClass();

            $class = 'Chrome_Request_'.self::$_requestClass;

            self::$_requestInstances[self::$_requestClass] = new $class();
            return self::$_requestInstances[self::$_requestClass];
        }
    }

    private static function _loadRequestClass()
    {
        $dir = LIB.self::CHROME_REQUEST_CLASS_PATH;

        // check wheter dir exists
        if(_isDir($dir) === false) {
            throw new Chrome_Exception('Cannot load any Chrome_Request classes, because dir '.LIB.self::CHROME_REQUEST_CLASS_PATH.' does not exist in Chrome_Request::_loadRequestClass()!');
        }

        $files = _getFilesInDir($dir);

        foreach($files AS $file) {

            if($file == self::$_requestClass.'.php') {
                require_once $dir.'/'.$file;
                return;
            }
        }

        throw new Chrome_Exception('Could not find file for class Chrome_Request_'.self::$_requestClass.'! Cannot load this class!');
    }

	/**
	 * Sets $this->_requestClass
	 *
	 * @param string $class name of the request class without prefix 'Chrome_Request_'
	 * @return void
	 */
	public static function setRequestClass($class)
	{
		self::$_requestClass = $class;
	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */ 
interface Chrome_Request_Interface
{
    public function &getParameters();

	public function getPrameterNames();

	public function issetParameter($name);

	public function getParameter($name);

	public function issetHeader($name);

	public function getHeader($name);

	public function getGET($name);

	public function getPOST($name);

	public function issetGET($name);

	public function issetPOST($name);

	public function &getGETParameter();

	public function &getPOSTParameter();

	public function setParams();

	public function setPOSTParameter($name, $data);

	public function setGETParameter($name, $data);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */ 
abstract class Chrome_Request_Abstract implements Chrome_Request_Interface {}