<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
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
 * @subpackage Chrome.Application
 */

namespace Chrome\Action;

use Psr\Http\Message\ServerRequestInterface;
interface Action_Interface
{
    public function isSent();

    public function isValid();
}

abstract class AbstractRequestAction implements Action_Interface
{
    /**
     * @var ServerRequestInterface
     */
    protected $_request = null;

    public function __construct(ServerRequestInterface $request)
    {
        $this->_request = $request;
    }
}