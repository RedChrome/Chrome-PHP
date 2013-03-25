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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [25.03.2013 17:03:08] --> $
 * @author     Alexander Book
 */
if(!defined('CHROME_TEST_ENVIRONMENT')) {
    define('CHROME_TEST_ENVIRONMENT', 4);
}
// unfortunately PHPUnit now needs $GLOBALS to be set, but we dont allow this, so use this hack
// and we need these vars to test Chrome_Session, Chrome_Cookie...
// they get unset in Chrome_Request_Factory, cause no code should access these global vars
$_tempServer = $_SERVER;
$_tempGlobals = $GLOBALS;
$_tempCookie = $_COOKIE;

require_once 'testsetup.php';
require_once 'testsetupdb.php';
require_once 'include/main.php';
Chrome_Front_Controller::getInstance();

global $applicationContext, $databaseContext;
$context = Chrome_Front_Controller::getInstance()->getApplicationContext();
$applicationContext = clone $context;
$applicationContext->setDatabaseFactory($databaseContext->getDatabaseFactory());


$_SERVER = $_tempServer;
$GLOBALS = $_tempGlobals;
$_COOKIE = $_tempCookie;
