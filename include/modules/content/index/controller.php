<?php

require_once 'model.php';
require_once 'view.php';
require_once 'include.php';

class Chrome_Controller_Index extends Chrome_Controller_Content_Abstract
{
	protected function _initialize()
	{
		$this->view = new Chrome_View_Index( $this );

		$this->model = new Chrome_Model_HTTP_Index();
		#$this->filter['postprocessor'][] = new Chrome_Filter_JSON();
	}

	protected function _execute()
	{

		$this->form = new Chrome_Form_Index();

		if( $this->form->isCreated() ) {

			if( $this->form->isSent() ) {

				if( $this->form->isValid() ) {
					$this->view->formIsValid();
				} else {
					$this->view->formIsInvalid();
				}
			} else {
				$this->view->formNotSent();
				$this->form->create();
			}
		} else {
			$this->view->formNotCreated();
			$this->form->create();
		}

		$this->data = $this->model->getAllData();

		$this->view->doSTH();

		$this->view->render();
	}
}