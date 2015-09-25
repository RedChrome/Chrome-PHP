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

    public function redirectToWebsite($website);

    public function redirectToResource(Resource_Interface $resource);
}
class Redirection implements Redirection_Interface
{
    protected $_applicationContext = null;

    public function __construct(\Chrome\Context\Application_Interface $context)
    {
        $this->_applicationContext = $context;
    }

    protected function _redirect($site)
    {
        $resp = $this->_applicationContext->getResponse();

        if($resp instanceof HTTP) {
            $resp->setStatus('303 Temporary Redirect');
            $resp->addHeader('Location', $site);
        }
    }

    public function redirectToPreviousPage()
    {
        $this->_redirect($this->getPreviousPage());
    }

    public function getPreviousPage()
    {
        $requestData = $this->_applicationContext->getRequestHandler()->getRequestData();

        if(($return = $requestData->getSERVERData('HTTP_REFERER')) != null)
        {
            return $return;
        } else
        {
            // we dont know where the user came, so get to the index.php
            return 'http://' . $requestData->getSERVERData('HTTP_HOST') . ROOT_URL;
        }
    }

    public function redirectToWebsite($website)
    {
        $this->_redirect($website);
    }

    public function redirectToResource(Resource_Interface $resource)
    {
        $linker = $this->_applicationContext->getViewContext()->getLinker();

        $this->_redirect($linker->get($resource));
    }
}