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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.11.2012 23:13:46] --> $
 * @author     Alexander Book
 */

if( !defined( 'CHROME_PHP' ) ) {
	define( 'CHROME_PHP', true );
}

$_SERVER['REQUEST_URI'] = '/root/CHROME_2/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla Firefox 5.0';
$_SERVER['SCRIPT_NAME'] = 'index.php';
$_SERVER['SERVER_NAME'] = "localhost";

require_once 'include/config.php';
require_once LIB . 'core/error/exception.php';

// Put here your vars for testing database connections
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'chrome-php');
define('MYSQL_PASS', 'chrome-php-password');
define('MYSQL_DB', 'chrome_2');
define('MYSQL_PORT', 3306);

define('TEST_DATABASE_CONNECTIONS', false);
