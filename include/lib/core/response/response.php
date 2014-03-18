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
 */

namespace Chrome\Response;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
interface Response_Interface
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
interface Handler_Interface
{
    public function canHandle();

    public function getResponse();
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
interface Factory_Interface
{
    public function getResponse();

    public function addResponseHandler(Handler_Interface $responseHandler);
}

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class Factory implements Factory_Interface
{
    protected $_responseHanlders = array();

    public function addResponseHandler(Handler_Interface $responseHandler)
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
