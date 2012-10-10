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
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [09.10.2012 13:01:37] --> $
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

    public static function redirectToWebsite($website);

    public static function redirectToResource(Chrome_Router_Resource_Interface $resource);
}


class Chrome_Redirection implements Chrome_Redirection_Interface
{
    protected function _redirect($site) {
        $resp = Chrome_Response::getInstance();
        $resp->setStatus('302 Redirect');
        $resp->addHeader('Location', $site);
    }

    public static function redirectToPreviousPage() {
        self::_redirect(self::getPreviousPage());
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

    public static function redirectToWebsite($website) {
        self::_redirect($website);
    }

    public static function redirectToResource(Chrome_Router_Resource_Interface $resource) {
        $resource->setReturnAsAbsolutPath(true);
        $url = Chrome_Router::getInstance()->url($resource);

        self::_redirect($url);
    }

}