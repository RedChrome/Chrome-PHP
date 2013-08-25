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
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 * @copyright Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version $Id: 0.1 beta <!-- phpDesigner :: Timestamp [20.03.2013 12:33:43] --> $
 * @author Alexander Book
 */
if(CHROME_PHP !== true)
    die();

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
class Chrome_Request_Handler_HTTP implements Chrome_Request_Handler_Interface
{
    protected $_requestData = null;

    public function canHandleRequest()
    {
        return isset($_SERVER['SERVER_PROTOCOL']) and isset($_SERVER['GATEWAY_INTERFACE']);
    }

    public function getRequestData()
    {
        if($this->_requestData === null)
        {
            $this->_requestData = new Chrome_Request_Data_HTTP();
        }

        return $this->_requestData;
    }
}
class Chrome_Request_Data_HTTP extends Chrome_Request_Data_Abstract
{
}
