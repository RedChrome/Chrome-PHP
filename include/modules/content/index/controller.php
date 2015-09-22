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

use \Chrome\Controller\AbstractModule;

require_once 'model.php';
require_once LIB.'core/view/form.php';
require_once 'view.php';
require_once 'include.php';

class Index extends AbstractModule
{
    protected function _initialize()
    {
        $factory = $this->_applicationContext->getViewContext()->getFactory();
        $this->_view = $this->_applicationContext->getDiContainer()->get('\Chrome\View\Index\Index');

        $this->_model = new \Chrome\Model\Index\Index();
    }

    protected function _execute()
    {
        $this->_form = new \Chrome\Form\Module\Index\Index($this->_applicationContext);

        #$obj = new \Chrome_Controller_User_Login_Page($this->_applicationContext);
        #$obj->execute();

        $obj = new \Chrome\Controller\User\Login($this->_applicationContext, $this->_applicationContext->getDiContainer()->get('\Chrome\Interactor\User\Login_Interface'));
        $obj->execute();

        $this->_view->addRenderable($obj->getView());
        $this->_view->addRenderable(new \Chrome\View\Index\ToDo($this->_applicationContext->getViewContext()));
        $view = new \Chrome\View\Index\Form($this->_form, $this->_applicationContext->getViewContext());

        return;
        $this->_view->addRenderable(new \Chrome\View\Index\FormRenderer($view));
        return;
        if( $this->_form->isCreated() ) {

            if( $this->_form->isSent() ) {

                if( $this->_form->isValid() ) {
                    $this->_view->formIsValid();
                } else {
                    $this->_form->create();
                    $this->_view->formIsInvalid();
                }
            } else {
                $this->_view->formNotSent();
                $this->_form->create();
            }
        } else {
            $this->_view->formNotCreated();
            $this->_form->create();
        }

        $this->_view->doSth();
    }
}
