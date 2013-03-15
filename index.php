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
 * @version   $Id: 0.1 beta <!-- phpDesigner :: Timestamp [13.03.2013 14:24:40] --> $
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
 *load front controller
 */

require_once 'include/main.php';

Chrome_Front_Controller::getInstance()->execute();
//TODO: set up Zend_Mail properly
//TODO: remove constants from form_elements, just use the ids... -> code reduction