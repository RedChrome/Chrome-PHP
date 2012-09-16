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
 * @subpackage Chrome.Router
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [16.09.2012 11:37:08] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true)
    die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Router
 */
class Chrome_Exception_Handler_Router implements Chrome_Exception_Handler_Interface
{
    protected $_caughtExceptions = array();

    public function exception(Exception $e)
    {
        $code = $e->getCode();

        // we already tried to route to another page, to prevent an infinity loop
        // we let the exception handle..
        if(in_array($e->getCode(), $this->_caughtExceptions)) {
            $code = -1;
        }

        switch($code) {

            // no route matched, so set route to a 404 site
            case 2001: {

                $url = new Chrome_URI(false);
                $url->setPath('404.html');

                Chrome_Router::getInstance()->route($url, Chrome_Request::getInstance()->getRequestDataObject());
                break;
            }


            default: {
                $handler = new Chrome_Exception_Handler_Default();
                $handler->exception($e);
            }
        }

        $this->_caughtExceptions[] = $e->getCode();
    }
}