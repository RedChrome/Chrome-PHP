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
 * @subpackage Chrome.Cache
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 13:52:15] --> $
 */

if(CHROME_PHP !== true)
    die();

/**
 *
 * Logger for database
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Log
 */
class Chrome_Logger_Database implements Chrome_Logger_Interface
{
    protected static $_logger = null;

    public function __construct()  {
        if(self::$_logger === null AND CHROME_LOG_SQL_ERRORS === true) {
            self::$_logger = new Chrome_Logger_File(TMP . CHROME_LOG_DIR . 'database');
        }
    }

    public function log($string, $mode)
    {
        if(self::$_logger !== null) {
            self::$_logger->log($string, $mode);
        }
    }
}