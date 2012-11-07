<?php

require_once 'model.php';
require_once 'view.php';
require_once 'include.php';

class Chrome_Controller_Index extends Chrome_Controller_Content_Abstract
{
	protected function _initialize()
	{
		$this->_view = new Chrome_View_Index( $this );

		$this->_model = new Chrome_Model_HTTP_Index();
		#$this->filter['postprocessor'][] = new Chrome_Filter_JSON();
	}

	protected function _execute()
	{

		$this->_form = new Chrome_Form_Index();

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

		$this->_data = $this->_model->getAllData();

		$this->_view->doSTH();

        $obj = new Chrome_Controller_User_Login_Page($this->_requestHandler);
        $obj->execute();

		$this->_view->render($this);
	}
}