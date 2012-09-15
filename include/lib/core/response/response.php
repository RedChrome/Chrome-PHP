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
 * @subpackage Chrome.Response
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.09.2012 02:14:51] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
interface Chrome_Response_Interface
{
	public function setStatus($status);

	public function addHeader($name, $value);

	public function write($string);

	public function flush();

	public function clear();

	public function setBody($string);

	public function getBody();

	public static function getInstance();
}

/**
 *
 * All classes (Chrome_Response_$SUFFIX) have to be saved in $suffix.php <-- only lower chars!
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
final class Chrome_Response
{
	const CHROME_RESPONSE_CLASS_PATH = 'core/response/';

	const CHROME_RESPONSE_DEFAULT_CLASS = 'HTTP';

	private static $_responseClass = null;

	private static $_responseInstance = null;

	private function __construct() {}

	private function __clone() {}

	public static function getInstance() {
		if(self::$_responseInstance === null) {
			self::$_responseInstance = self::_createResponseInstance();
		}
		return self::$_responseInstance;
	}

	public static function setResponseClass($class) {
	    $class = strtoupper($class);
		$_class = 'Chrome_Response_'.$class;
		$class .= '.php';
		if(!_isDir(LIB.self::CHROME_RESPONSE_CLASS_PATH)) {
			throw new Chrome_Exception('Cannot find include path for Chrome_Response classes in Chrome_Response::setResponseClass()!');
		}

        /**
        $files = _getFilesInDir(LIB.self::CHROME_RESPONSE_CLASS_PATH);

		if(($key = array_search($class, $files)) === false) {
			throw new Chrome_Exception('Cannot find file '.LIB.self::CHROME_RESPONSE_CLASS_PATH.$class.' in Chrome_Response::setResponseClass()!');
		} else {
			require_once $files[$key];
		}*/

        // faster
        if(!_isFile(LIB.self::CHROME_RESPONSE_CLASS_PATH.$class)) {
            throw new Chrome_Exception('Cannot find file '.LIB.self::CHROME_RESPONSE_CLASS_PATH.$class.' in Chrome_Response::setResponseClass()!');
        } else {
            require_once LIB.self::CHROME_RESPONSE_CLASS_PATH.$class;
        }

        // unneeded check, if class not exists, then there will be an error ...
		//if(!class_exists($_class, false)) {
		//	throw new Chrome_Exception('Cannot create an instance of '.$_class.'! Class is not defined in file '.LIB.self::CHROME_RESPONSE_CLASS_PATH.$class.' in Chrome_Response::setResponseClass()!');
		//}

		self::$_responseClass = $_class;
	}

	private static function _createResponseInstance() {

		if(self::$_responseClass === null) {
			self::setResponseClass(self::CHROME_RESPONSE_DEFAULT_CLASS);
		}

		return call_user_func(array(self::$_responseClass, 'getInstance'));
	}
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
abstract class Chrome_Response_Abstract implements Chrome_Response_Interface {}