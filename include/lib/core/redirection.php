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
 * @subpackage Chrome.Session
 */

namespace Chrome\Redirection;

use \Chrome\Resource\Resource_Interface;
use Chrome\Response\Handler\HTTP;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Misc
 */
interface Redirection_Interface
{
    public function redirectToPreviousPage();

    public function reload($sec);

    public function redirectToResource(Resource_Interface $resource);
}

class Redirection implements Redirection_Interface
{
    protected $_applicationContext = null;

    protected $_linker = null;

    public function __construct(\Chrome\Context\Application_Interface $context)
    {
        $this->_applicationContext = $context;
        $this->_linker = $context->getViewContext()->getLinker();
    }

    protected function _redirect($site)
    {
        $resp = $this->_applicationContext->getResponse();

        if($resp instanceof \Chrome\Response\HTTP) {
            $resp->setStatus('303 Temporary Redirect');
            $resp->addHeader('Location', $site);
        }
    }

    public function reload($sec)
    {
        $resp = $this->_applicationContext->getResponse();

        if($resp instanceof \Chrome\Response\HTTP) {
            $resp->addHeader('Refresh', (int) $sec);
        }
    }

    public function redirectToPreviousPage()
    {
        $this->_redirect($this->getPreviousPage());
    }

    public function getPreviousPage()
    {
        $request = $this->_applicationContext->getRequestContext()->getRequest();
        $data = $request->getServerParams();

        $return = isset($data['HTTP_REFERER']) ? $data['HTTP_REFERER'] : null;

        if($return != null)
        {
            return $return;
        } else
        {
            return $this->_linker->get(new \Chrome\Resource\Relative(''));
        }
    }

    public function redirectToResource(Resource_Interface $resource)
    {
        $this->_redirect($this->_linker->get($resource));
    }
}