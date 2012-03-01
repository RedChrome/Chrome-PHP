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
 * @subpackage Chrome.Require
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [18.12.2010 15:02:48] --> $
 */

class Chrome_Require_Controller implements Chrome_Require_Interface
{
    private static $_instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
    	if(self::$_instance === null) {
    		self::$_instance = new self();
    	}

       	return self::$_instance;
    }

    public function classLoad($class)
    {
		if(preg_match('#Chrome_Controller_(.{1,})#i', $class, $matches)) {

            $matches[1] = str_replace('_Abstract', '', $matches[1]);

			$file = strtolower($matches[1].'.php');

			if(_isFile(LIB.'core/controller/'.$file)) {
				return LIB.'core/controller/'.$file;
			} else {
				throw new Chrome_Exception('Could not load class '.$class.'! The file '.LIB.'core/Controller/'.$file.' was not found in Chrome_Require_Controller::classLoad()!');
			}
		}
    }
}