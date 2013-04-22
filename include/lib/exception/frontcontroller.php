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
 * @subpackage Chrome.FrontController
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [15.04.2013 10:52:09] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.FrontController
 */
class Chrome_Exception_Handler_FrontController implements Chrome_Exception_Handler_Interface
{
    public function exception(Exception $e)
    {
        switch(get_class($e)) {

            case 'Chrome_Exception_Database': {
                die('There was an error with the database.');
            }

            default: {

                die('There was an error in processing the request! Please try it again later!<br>');
            }
        }
    }
}