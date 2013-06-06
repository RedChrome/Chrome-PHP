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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [10.05.2013 17:24:26] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * Loads all classes beginning with 'Chrome_Design_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Require_Loader_Design extends Chrome_Require_Loader_Abstract
{
	/**
	 * Loads a class, if $class beginns with 'Chrome_Design_'
	 *
	 * @param string $class
	 * @return bool true if class was found
	 */
	public function loadClass($class)
	{
		if(preg_match('#Chrome_Design_Composite_(.{1,})#i', $class, $matches)) {
			return LIB.'core/design/composite/'.strtolower($matches[1]).'.php';

		} else
			if(preg_match('#Chrome_Design_Decorator_([a-z1-9]{1,})_([a-z1-9]{1,})_(.{1,})#iu', $class, $matches)) {
				return LIB.'core/design/decorator/default/'.strtolower($matches[2]).'/'.strtolower($matches[3]).'.php';
			} else
				if(preg_match('#Chrome_Design_Factory_Decorator_(.{1,})#i', $class, $matches)) {
					return LIB.'core/design/factory/decorator/'.strtolower($matches[1]).'.php';
				}

		return false;
	}
}
