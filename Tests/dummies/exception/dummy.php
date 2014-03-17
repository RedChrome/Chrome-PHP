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
 * @subpackage Chrome.Test
 */

namespace Test\Chrome;

use \Chrome\Exception;

/**
 * DummyException
 *
 * This is supposed to be a development code! You should not use this in production.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Test
 */
class DummyException extends Exception
{
}

namespace Test\Chrome\Exception\Handler;

/**
 * DummyHandler
 *
 * This is supposed to be a development code! You should not use this in production.
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Test
 */
class DummyHandler implements \Chrome\Exception\Handler_Interface
{
    protected $_echoText = false;

    public function __construct($echoText = false)
    {
        $this->_echoText = $echoText;
    }

    public function exception(\Exception $e)
    {
        if($this->_echoText === true)
        {
            echo 'There was an exception of type ' . get_class($e) . ' with message ' . $e->getMessage();
            var_dump($e);
        }
    }
}