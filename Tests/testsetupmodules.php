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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [03.03.2013 13:43:10] --> $
 * @author     Alexander Book
 */

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

$_SERVER = $_tempServer;
$GLOBALS = $_tempGlobals;
$_COOKIE = $_tempCookie;

class Chrome_Test extends PHPUnit_Framework_TestCase
{
    protected $_session, $_cookie;

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        $this->_session = Chrome_Front_Controller::getInstance()->getRequestHandler()->getRequestData()->getSession();
        $this->_cookie  = Chrome_Front_Controller::getInstance()->getRequestHandler()->getRequestData()->getCookie();
        parent::__construct($name, $data, $dataName);
    }


    public function test() {
        // do nothing, just to avoid a warning "No tests found in class Chrome_Test"
    }
}