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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.05.2013 17:24:45] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * Loads all classes beginning with 'Chrome_Form_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Require_Loader_Form extends Chrome_Require_Loader_Abstract
{
	/**
	 * Checks whether this class knows where the other class is located
	 *
	 * @param stinrg $class name of the class
	 * @return boolean
	 * @throws Chrome_Exception
	 */
	public function loadClass($class)
	{
		if(preg_match('#Chrome_Form_Element_(.{1,})#i', $class, $matches)) {
			return LIB.'core/form/element/'.strtolower($matches[1]).'.php';
		}

		if(preg_match('#Chrome_Form_Decorator_(.{1,})#i', $class, $matches)) {
			return LIB.'core/form/decorator/'.strtolower(str_replace('_', '/', $matches[1])).'.php';
		}

		if(preg_match('#Chrome_Form_Handler_(.{1,})#i', $class, $matches)) {
			return LIB.'core/form/handler/'.strtolower($matches[1]).'.php';
		}

       	if(preg_match('#Chrome_Form_Storage_(.{1,})#i', $class, $matches)) {
			return LIB.'core/form/storage/'.strtolower($matches[1]).'.php';
		}

		return false;
	}
}
