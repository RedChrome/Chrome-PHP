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

namespace Chrome\Response\Handler;

use \Chrome\Response\Handler_Interface;
use \Chrome\Response\Response_Interface;

require_once 'http.php';

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class JSONHandler extends HTTPHandler
{
    public function canHandle()
    {
        if(parent::canHandle() === false) {
           return false;
        }

        $requestData = $this->_request->getRequestData();

        return (strtolower($requestData->getSERVERData('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest' OR strtolower($requestData->getREQUESTData('ajax')) === 'true' );
    }

    public function getResponse()
    {
        return new \Chrome\Response\JSON($this->_request->getRequestData()->getSERVERData('HTTP_PROTOCOL'));
    }
}

namespace Chrome\Response;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Response
 */
class JSON extends HTTP
{
    protected $_body = array();

    protected $_headers = array('Content-Type' => 'application/json');

    public function __destruct()
    {
        if(count($this->_body) > 0) {
          $this->flush();
        }
    }

    public function write($array)
    {
        if(!is_array($array)) {
           $array = array($array);
        }

        $this->_body = array_merge($array, $this->_body);
    }

    public function flush()
    {
        $this->_printHeaders();

        if(is_array($this->_body)) {
            echo json_encode($this->_body);
        } else {
            echo $this->_body;
        }

        $this->_headers = array();
        $this->_body = array();
    }

    public function clear()
    {
        $this->_body = array();
    }
}
