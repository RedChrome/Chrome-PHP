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
 * @subpackage Chrome.Response
 * @copyright  Copyright (c) 2008-2012 Chrome - PHP (http://www.chrome-php.de)
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Create Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [19.03.2013 20:38:16] --> $
 * @author     Alexander Book
 */

if(CHROME_PHP !== true) die();

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
interface Chrome_Response_Interface
{
    public function write($mixed);

    public function flush();

    public function clear();

    public function setBody($mixed);

    public function getBody();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
interface Chrome_Response_Handler_Interface
{
    public function canHandle();

    public function getResponse();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
interface Chrome_Response_Factory_Interface
{
    public function getResponse();

    public function addResponseHandler(Chrome_Response_Handler_Interface $responseHandler);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Chrome_Response_Factory implements Chrome_Response_Factory_Interface
{
    protected $_responseHanlders = array();

    public function addResponseHandler(Chrome_Response_Handler_Interface $responseHandler)
    {
        $this->_responseHanlders[] = $responseHandler;
    }

    public function getResponse()
    {
        foreach($this->_responseHanlders as $responseHandler) {

            if($responseHandler->canHandle() === true) {
                return $responseHandler->getResponse();
            }

        }
    }
}
