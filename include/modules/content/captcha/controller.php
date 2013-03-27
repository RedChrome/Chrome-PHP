<?php

require_once 'view.php';
require_once 'include.php';

class Chrome_Controller_Captcha extends Chrome_Controller_Module_Abstract
{
	protected function _initialize()
	{
        $this->_view = new Chrome_View_Captcha($this);
	}

	protected function _execute()
	{
	    $this->_form = new Chrome_Form_Captcha($this->_requestHandler);

	    if(!$this->_form->isCreated()) {
	       $this->_form->create();
	    } else if(!$this->_form->isSent()) {
	       $this->_form->renew();
	    } else if(!$this->_form->isValid()) {
	       $this->_form->renew();
	    } else {
	       $this->_view->formValid();
	    }


        $this->_view->test();
	}
}