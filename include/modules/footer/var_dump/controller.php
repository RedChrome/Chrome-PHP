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

namespace Chrome\Controller\Footer;

use \Chrome\Controller\ModuleAbstract;

require_once MODULE.'footer/var_dump/view.php';

/**
 * VarDump
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Controller
 */
class VarDump extends ModuleAbstract
{
    protected function _execute()
    {
        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_Footer_VarDump', $this);
        $this->_view->setData($this->_requestData->getData(), $this->_requestData->getCookie(), $this->_requestData->getSession());
    }
}