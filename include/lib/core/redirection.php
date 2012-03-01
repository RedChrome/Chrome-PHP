<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Session
 * @copyright  Copyright (c) 2008-2009 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://chrome-php.de/license/new-bsd		New BSD License
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [01.03.2012 16:57:11] --> $
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

        if(isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        } else {

            // we dont know where the user came, so get to the index.php
            return ROOT_URL;
        }
    }

}