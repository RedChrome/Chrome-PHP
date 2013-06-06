<?php

require_once 'model.php';
require_once 'view.php';
require_once 'include.php';

class Chrome_Controller_Index extends Chrome_Controller_Module_Abstract
{
	protected function _initialize()
	{
	    $factory = $this->_applicationContext->getViewContext()->getFactory();
		$this->_view = $factory->build('Chrome_View_Index', $this);

		$this->_model = new Chrome_Model_HTTP_Index();
	}

	protected function _execute()
	{
		$this->_form = new Chrome_Form_Index($this->_requestHandler);

        $obj = new Chrome_Controller_User_Login_Page($this->_applicationContext);
        $obj->execute();

        $this->_view->addRenderable($obj->getView());

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

		$this->_view->doSTH();
	}
}