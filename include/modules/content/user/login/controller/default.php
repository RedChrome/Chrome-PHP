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
 * @subpackage Chrome.User
 */

/**
 *
 * @package CHROME-PHP
 * @subpackage Chrome.User
 */
class Chrome_Controller_Content_Login_Default extends \Chrome\Controller\ModuleAbstract
{
    public function __construct(Chrome_Context_Application_Interface $appContext, \Chrome\Interactor\User\Login $interactor)
    {
        parent::__construct($appContext);
        $this->_interactor = $interactor;
    }

    protected function _initialize()
    {
        $this->_require = array('file' => array(CONTENT . 'user/login/include.php', CONTENT . 'user/login/view/default.php', CONTENT . 'user/login/model.php'));
    }

    protected function _handleForm()
    {
        if($this->_applicationContext->getAuthentication()->isUser() == true)
        {
            $this->_view->alreadyLoggedIn();
        } else
        {
            try
            {
                if($this->_form->isSent())
                {
                    if($this->_form->isValid())
                    {
                        $this->_interactor->login($this->_form->getSentData('identity'), $this->_form->getSentData('password'), $this->_form->getSentData('stay_loggedin'));

                        if($this->_interactor->isLoggedIn() === true)
                        {
                            $this->_view->successfullyLoggedIn();
                        } else
                        {
                            $this->_view->errorWhileLoggingIn($this->_applicationContext->getDiContainer()->get('\Chrome\View\Form\Element\Factory\Default'));
                        }
                    } else
                    {
                        $this->_form->destroy();
                        $this->_form->create();
                        $this->_view->formNotValid();
                    }
                } else
                {
                    $this->_view->showForm($this->_form, $this->_applicationContext->getDiContainer()->get('\Chrome\View\Form\Element\Factory\Default'));
                }
            } catch(\Chrome\Exception $e)
            {
                $this->_exceptionHandler->exception($e);
            }
        }
    }

    protected function _execute()
    {
        $this->_form = Chrome_Form_Login::getInstance($this->_applicationContext);

        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_User_Login_Default', $this);

        //$this->_model = new Chrome_Model_Login($this->_applicationContext, $this->_form);

        $this->_form->create();

        $this->_handleForm();
    }
}