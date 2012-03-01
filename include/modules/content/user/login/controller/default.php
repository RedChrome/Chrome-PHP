<?php

class Chrome_Controller_Content_Login_Default extends Chrome_Controller_Content_Abstract
{
	protected function _initialize() {
	    $this->require = array('file' => array(CONTENT.'user/login/include.php', CONTENT.'user/login/view/default.php', CONTENT.'user/login/model.php'));
	}

    protected function _execute()
    {
        $this->form = Chrome_Form_Login::getInstance();

        $this->view = new Chrome_View_User_Login_Default($this);

        $this->model = new Chrome_Model_Login($this->form);

        if($this->model->isLoggedIn() == true) {
            $this->view->alreadyLoggedIn();
        } else {

            try {
                if($this->form->isSent()) {

                    if($this->form->isValid()) {

                        // try to log in
                        $this->model->login();

                        if($this->model->successfullyLoggedIn() === true) {
                            $this->view->successfullyLoggedIn();
                        } else {
                            $this->view->errorWhileLoggingIn();
                        }

                    } else {
                        $this->form->delete();
                        $this->form->create();
                        $this->view->formNotValid();
                    }

                } else {
                    $this->view->showForm();
                }
            } catch(Chrome_Exception $e) {
                $e->show($e);
            }
        }
        $this->view->render();
    }
}