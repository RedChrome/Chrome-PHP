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
 * @subpackage Chrome.Authentication
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.02.2013 14:07:15] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Exception_Authentication extends Chrome_Exception
{

}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Authentication
 */
class Chrome_Exception_Handler_Authentication implements Chrome_Exception_Handler_Interface
{
    public function exception(Exception $e)
    {
        if(!($e instanceof Chrome_Exception_Authentication)) {
            $e->show($e);
        }

        Chrome_Log::log('Exception in Chrome_Authentication! Error code: '.$e->getCode() .'. Message: '.$e->getMessage(), E_ERROR);
        Chrome_Log::log($e->getTraceAsString(), E_ERROR);

        die('Error in authentication! See log files for more information');
    }
}