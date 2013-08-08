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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.02.2013 14:14:24] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * Chrome_Exception_Dummy
 *
 * This is supposed to be a development code! You should not use this in production.
 *
 * @package CHROME-PHP
 */
class Chrome_Exception_Dummy extends Chrome_Exception
{

}

/**
 * Chrome_Exception_Handler_Dummy
 *
 * This is supposed to be a development code! You should not use this in production.
 *
 * @package CHROME-PHP
 */
class Chrome_Exception_Handler_Dummy implements Chrome_Exception_Handler_Interface
{
    protected $_echoText = false;

    public function __construct($echoText = false) {
        $this->_echoText = $echoText;
    }

    public function exception(Exception $e)
    {
        if($this->_echoText === true) {
            echo 'There was an exception of type '.get_class($e).' with message '.$e->getMessage();
            var_dump($e);
        }
    }
}