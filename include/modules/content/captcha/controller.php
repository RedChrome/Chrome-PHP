<?php

require_once 'view.php';
require_once 'include.php';

class Chrome_Controller_Captcha extends Chrome_Controller_Module_Abstract
{
    protected function _initialize()
    {
        $this->_view = $this->_applicationContext->getViewContext()->getFactory()->build('Chrome_View_Captcha', $this);
    }

    protected function _execute()
    {
        $this->_form = new Chrome_Form_Captcha($this->_applicationContext);

        if(!$this->_form->isCreated()) {
           $this->_form->create();
        } else if(!$this->_form->isSent()) {
           $this->_form->renew();
        } else if(!$this->_form->isValid()) {
           $this->_form->renew();
        } else {
           $this->_view->formValid($this->_form);
        }

        $this->_view->test($this->_form, $this->_applicationContext->getDiContainer()->get('\Chrome\View\Form\Element\Factory\Default'));
    }
}