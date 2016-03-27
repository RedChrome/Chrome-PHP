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
 * @subpackage Chrome.User
 */
namespace Chrome\Controller\User;

use \Chrome\Controller\AbstractModule;

class Login extends AbstractModule
{

    public function __construct(\Chrome\Interactor\User\Login $interactor, \Chrome\Form\Module\User\Login $form, \Chrome\View\User\Login $view)
    {
        $this->_interactor = $interactor;
        $this->_form = $form;
        $this->_view = $view;
    }

    protected function _execute()
    {
        if($this->_applicationContext->getAuthentication()->isUser() === true)
        {
            $this->_view->alreadyLoggedIn();
            return;
        }

        $this->_form->create();

        if($this->_form->isSent() and $this->_form->isValid())
        {
            $this->_interactor->login($this->_form->getData('identity'), $this->_form->getData('password'), $this->_form->getData('stay_loggedin'));

            if($this->_interactor->isLoggedIn() === true)
            {
                $this->_view->successfullyLoggedIn();
                return;
            }
        }

        $this->_view->displayLogin($this->_form, $this->_applicationContext->getDiContainer()->get('\Chrome\View\Form\Module\User\Login'));
    }
}