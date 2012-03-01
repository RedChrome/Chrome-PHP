<?php

require_once 'model.php';
require_once 'view.php';
require_once 'include.php';

class Chrome_Controller_Register extends Chrome_Controller_Content_Abstract
{
    const CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE = 'REGISTER';

    protected function _initialize()
    {
        $this->view = new Chrome_View_Register($this);
    }

    protected function _execute()
    {
        if(Chrome_Authorisation::getInstance()->isAllowed(new Chrome_RBAC_Resource('register', 'register')) === false) {
            $this->view->alreadyRegistered();
            //$this->view->setError(403);
            $this->view->render();
            return;
        }

        $session = Chrome_Session::getInstance();

        if(!isset($session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE]) ) {

            $this->_stepOne();

        } else {

            switch($session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE]['step']) {

                case 2: {
                    $this->form = new Chrome_Form_Register_StepOne();

                    if(!$this->form->isCreated() OR !$this->form->isSent() OR !$this->form->isValid()) {

                        if(!$this->form->isValid()) {
                            $this->form->create();
                        }

                        $this->_stepOne();
                        break;
                    }

                    $this->_stepTwo();
                    break;
                }

                case 3: {

                    $this->form = new Chrome_Form_Register_StepTwo();

                    if(!$this->form->isCreated() OR !$this->form->isSent() OR !$this->form->isValid()) {

                        $data = $this->form->getData();

                        // go one step back
                        if($this->form->isSent('backward')) {
                            $this->form = new Chrome_Form_Register_StepOne();
                            $this->form->create();
                            $this->_stepOne();
                        } else {
                            // process the errors
                            $this->_stepTwo();
                        }

                        break;
                    }

                    $this->model = Chrome_Model_Register::getInstance();
                    $this->model->sendRegisterEmail($this->form->getSentData('email'));

                    $this->_stepThree();

                    break;
                }

                case 4: {

                    $this->_stepThree();
                    break;
                }

                default: {
                    // should never happen
                    $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array('step' => 2);
                    throw new Chrome_Exception('Undefined step in registration!');
                }
            }
        }

        $this->view->render();
    }

    private function _stepOne() {

        if($this->form == null) {
            $this->form = new Chrome_Form_Register_StepOne();
        }

        if(!$this->form->isCreated()) {
            $this->form->create();
        }

        $this->view->setStepOne();

        $session = Chrome_Session::getInstance();

        $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array('step' => 2);
    }

    private function _stepTwo() {

        if(!($this->form instanceof Chrome_Form_Register_StepTwo)) {
            $this->form = new Chrome_Form_Register_StepTwo();
        }

        $this->form->create();


        $this->view->setStepTwo();

        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 3;
        $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }

    private function _stepThree() {

        $this->view->setStepThree();

        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 4;
        $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }
}