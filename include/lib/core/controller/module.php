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
 * @subpackage Chrome.Controller
 */

namespace Chrome\Controller;

use Chrome\Controller\AbstractController;

/**
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
abstract class AbstractModule extends AbstractController
{
    public function __construct(\Chrome\Context\Application_Interface $appContext)
    {
        parent::__construct($appContext);

        $this->_initialize();

        $this->_require();
    }

    final public function execute()
    {
        $this->_execute();

        $this->_shutdown();
    }

    protected function _initialize()
    {

    }

    protected function _execute()
    {

    }

    protected function _shutdown()
    {

    }
}
