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

use \Chrome\Controller\AbstractModule;

require_once MODULE.'footer/var_dump/view.php';

/**
 * VarDump
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.Controller
 */
class VarDump extends AbstractModule
{
    protected function _execute()
    {
        $this->_view = $this->_applicationContext->getDiContainer()->get('\Chrome\View\Footer\VarDump');

        $this->_view->setData(
                array('GET' => $this->_request->getQueryParams(), 'POST' => $this->_request->getParsedBody(), 'FILES' => $this->_request->getUploadedFiles(),
                        'SERVER' => $this->_request->getServerParams(), 'HEADERS' => $this->_request->getHeaders()
                ), $this->_requestContext->getCookie(), $this->_requestContext->getSession());
    }
}
