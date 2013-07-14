<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @category  CHROME-PHP
 * @package   CHROME-PHP
 * @author    Alexander Book <alexander.book@gmx.de>
 * @copyright 2012 Chrome - PHP <alexander.book@gmx.de>
 * @license   http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [29.06.2013 23:59:34] --> $
 * @link      http://chrome-php.de
 */
// debugin...
if( !isset( $_SERVER['REMOTE_ADDR'] ) ) {
	$_SERVER['REMOTE_ADDR']     = '127.0.0.1';
	$_SERVER['HTTP_USER_AGENT'] = 'Mozilla Firefox 5.0';
	$_SERVER['REQUEST_URI']     = '';
	$_SERVER['SCRIPT_NAME']     = 'index.php';
	$_SERVER['SERVER_NAME']     = 'localhost';
}

/**
 * load chrome-php framework
 */
require_once 'include/chrome.php';
require_once APPLICATION.'default.php';

$application = new Chrome_Application_Default();
$application->init();
$application->execute();
//TODO: set up Zend_Mail properly
//TODO: remove constants from form_elements, just use the ids... -> code reduction
//TODO: remove converters from form. they have to get moved to model..
//TODO: remove all forms from controller, replace them by models (Chrome_Model_Form_Abstract)
//TODO: remove default decorator and set default decorator to yaml
//TODO: render errors in forms correct with ym-error and ym-message
//TODO: decide whether view or controller can change the theme...