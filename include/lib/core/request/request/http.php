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
 */

namespace Chrome\Request\Handler;

use \Chrome\Hash\Hash_Interface;
use \Chrome\Request\Handler_Interface;
use \Chrome\Request\DataAbstract;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Request
 */
class HTTPHandler implements Handler_Interface
{
    protected $_requestData = null;

    protected $_hash = null;

    public function __construct(Hash_Interface $hash)
    {
        $this->_hash = $hash;
    }

    public function canHandleRequest()
    {
        return isset($_SERVER['SERVER_PROTOCOL']) and isset($_SERVER['GATEWAY_INTERFACE']);
    }

    public function getRequestData()
    {
        if($this->_requestData === null)
        {
            $this->_requestData = new HTTPData($this->_hash);
        }

        return $this->_requestData;
    }
}
class HTTPData extends DataAbstract
{
}
