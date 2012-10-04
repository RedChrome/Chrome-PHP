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
 * @subpackage Chrome.Session
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [04.10.2012 00:42:53] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Misc
 */

interface Chrome_Redirection_Interface
{
    public static function redirectToPreviousPage();
}


class Chrome_Redirection implements Chrome_Redirection_Interface
{
    public static function redirectToPreviousPage() {
        Chrome_Response::getInstance()->addHeader('Location', self::getPreviousPage());
    }

    public static function getPreviousPage() {

        $requestDataObject = Chrome_Request::getInstance()->getRequestDataObject();

        if(($return = $requestDataObject->getSERVER('HTTP_REFERER')) != null) {
            return $return;
        } else {

            // we dont know where the user came, so get to the index.php
            return 'http://'.$requestDataObject->getSERVER('HTTP_HOST').ROOT_URL;
        }
    }

}