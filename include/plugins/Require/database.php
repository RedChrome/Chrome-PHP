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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [06.03.2013 16:42:28] --> $
 */

if(CHROME_PHP !== true) die();

/**
 * Loads all classes beginning with 'Chrome_Database_'
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Require
 */
class Chrome_Require_Loader_Design implements Chrome_Require_Loader_Interface
{
	/**
	 * Loads a class, if $class beginns with 'Chrome_Database_'
	 *
	 * @param string $class
	 * @return bool true if class was found
	 */
	public function loadClass($class)
	{
        if(preg_match('#Chrome_Database_([a-z1-9]{1,})_(.{1,})#iu', $class, $matches))
		{
			return LIB . 'core/database/'.strtolower($matches[1]).'/' . strtolower($matches[2]) . '.php';
		}

        return false;
	}

}
