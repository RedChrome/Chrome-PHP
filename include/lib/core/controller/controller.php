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
 * @subpackage Chrome.Controller
 */

namespace Chrome\Controller;

use \Chrome\Exception\Processable_Interface;

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
interface Controller_Interface extends Processable_Interface
{
    /**
     * execute()
     *
     * @return void
     */
    public function execute();

    /**
     * Sets the application context
     *
     * @param \Chrome\Context\Application_Interface $appContext
     * @return void
     */
    public function setApplicationContext(\Chrome\Context\Application_Interface $appContext);

    /**
     * Returns the application context
     *
     * @return \Chrome\Context\Application_Interface
     */
    public function getApplicationContext();

    /**
     * Returns the view for the controller (if set)
     *
     * @return \Chrome\View\View_Interface|null
     */
    public function getView();
}

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.Controller
 */
abstract class AbstractController implements Controller_Interface
{
    use \Chrome\Exception\ProcessableTrait;

    /**
     * Contains the context to the current application
     *
     * @var \Chrome\Context\Application_Interface
     */
    protected $_applicationContext = null;

    /**
     * An instance of an interactor
     *
     * @var \Chrome\Interactor\Interactor_Interface
     */
    protected $_interactor = null;

    /**
     * @var Chrome_View_Interface
     */
    protected $_view = null;

    /**
     *
     * @var \Chrome\Form\Form_Interface
     */
    protected $_form = null;

    /**
     *
     * @var \Chrome\Request\RequestContext_Interface
     */
    protected $_requestContext = null;

    /**
     *
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $_request = null;

    public function getView()
    {
        return $this->_view;
    }

    public function setApplicationContext(\Chrome\Context\Application_Interface $appContext)
    {
        $this->_applicationContext = $appContext;
        $this->_setRequestContext($appContext->getRequestContext());
    }

    public function getApplicationContext()
    {
        return $this->_applicationContext;
    }

    protected function _setRequestContext(\Chrome\Request\RequestContext_Interface $obj)
    {
        $this->_requestContext = $obj;
        $this->_request = $obj->getRequest();
    }
}
