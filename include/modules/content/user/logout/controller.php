<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://chrome-php.de/license/new-bsd
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Controller
 */

namespace Chrome\Controller\User;

use \Chrome\Controller\AbstractModule;
use \Chrome\Context\Application_Interface;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
class Logout extends AbstractModule
{
    protected $_logout = null;

    public function __construct(\Chrome\Interactor\User\Logout $logout)
    {
        $this->_logout = $logout;
    }

    protected function _execute()
    {
        $this->_logout->doLogout();
    }
}