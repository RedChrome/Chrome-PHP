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
 * @subpackage Chrome.Exception
 */

namespace Chrome\Exception\Handler;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.FrontController
 */
class FrontControllerHandler extends LoggableHandlerAbstract
{
    public function exception(\Exception $e)
    {
        $this->_logger->error($e);

        switch(get_class($e))
        {
            case '\Chrome\DatabaseException':
                {
                    die('There was an error with the database.');
                }

            default:
                {
                    die('There was an error in processing the request! Please try it again later!<br>');
                }
        }
    }
}