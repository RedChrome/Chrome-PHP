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
 * @subpackage Chrome.Exception
 */

namespace Chrome\Exception\Handler;

use \Chrome\Exception\Handler_Interface;

/**
 * @package CHROME-PHP
 */
class Json implements Handler_Interface
{
    public function exception(\Exception $e)
    {
        echo json_encode(array('error' => $e->getMessage()));
        die();
    }
}