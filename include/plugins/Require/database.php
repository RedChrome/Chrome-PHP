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
 * @subpackage Chrome.Database
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.03.2013 16:18:15] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

/**
 * Autoloader for database classes
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Require.Loader
 */
class Chrome_Require_Loader_Database implements Chrome_Require_Loader_Interface
{
    /**
     * loads the corresponding file for $className
     *
     * Loads the file if $className beginns with 'Chrome_Database_'
     * and the file exists.
     *
     * @param string $className
     * @return boolean true if file could get loaded
     */
	public function loadClass($className)
	{
		if(preg_match('#Chrome_Database_([a-z1-9]{1,})_(.{1,})#i', $className, $matches)) {

            return LIB.'core/database/'.strtolower($matches[1]).'/'.strtolower($matches[2]).'.php';
		}
		return false;
	}

    public function init(Chrome_Require_Autoloader_Interface $autoloader) {
        // do nothing
    }
}