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
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [18.12.2010 14:57:19] --> $
 */

if(CHROME_PHP !== true)
	die();

/**
 * Chrome_Require_Exception
 *
 * ___SHORT_DESCRIPTION___
 *
 *
 * @author		Alexander Book
 * @package		CHROME-PHP
 * @copyright   Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license     http://chrome-php.de/license/new-bsd		New BSD License
 * @version		2009/04/08/15/25
 */
class Chrome_Require_Validator implements Chrome_Require_Interface
{
	private static $_instance;

	private function __construct() {

	}

	public static function getInstance() {
  		if(self::$_instance === null) {
  			self::$_instance = new self();
  		}

  		return self::$_instance;
	}

 	public function classLoad($class) {
 		// does the class contain 'Chrome_Validator_'?
		if(preg_match('#Chrome_Validator_(.{1,})#i', $class, $matches)) {

            $file = strtolower(str_replace('_', '/', $matches[1]));

			if(_isFile( BASEDIR.'plugins/Validate/'.$file.'.php')) {
				return BASEDIR.'plugins/Validate/'.$file.'.php';
			} else {
				throw new Chrome_Exception('Cannot load class '.$class.'! There is no file matching the requirements in Chrome_Require_Validator::classLoad()!');
			}
		}

        return false;
 	}
}