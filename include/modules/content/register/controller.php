<?php

require_once 'model.php';
require_once 'view.php';
require_once 'include.php';

class Chrome_Controller_Register extends Chrome_Controller_Content_Abstract
{
    const CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE = 'REGISTER';

    protected $_activationKey;

    protected function _initialize()
    {
        //TODO: move those out of class
        $this->_view = new Chrome_View_Register($this);
        $this->_model = Chrome_Model_Register::getInstance();
    }

    protected function _execute()
    {
        if(Chrome_Authorisation::getInstance()->isAllowed(new Chrome_Authorisation_Resource('register', 'register')) === false) {
            $this->_view->alreadyRegistered();
            //$this->view->setError(403);
            $this->_view->render($this);
            return;
        }

        $session = Chrome_Session::getInstance();

        if($this->_requestData->getGET('action') === 'register') {

            if(!isset($session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE])) {

                $this->_stepOne();

            } else {

                switch($session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE]['step']) {

                    case 2:
                        {
                            $this->_form = new Chrome_Form_Register_StepOne();

                            if(!$this->_form->isCreated() or !$this->_form->isSent() or !$this->_form->isValid()) {

                                if(!$this->_form->isValid()) {
                                    $this->_form->create();
                                }

                                $this->_stepOne();
                                break;
                            }

                            $this->_stepTwo();
                            break;
                        }

                    case 3:
                        {

                            $this->_form = new Chrome_Form_Register_StepTwo();

                            $data = $this->_form->getData();

                            // go one step back
                            if($this->_form->isSent('buttons') and isset($data['buttons']['backward'])) {
                                $this->_form = new Chrome_Form_Register_StepOne();
                                $this->_form->create();
                                $this->_stepOne();
                                break;
                            }

                            // process the errors
                            if(!$this->_form->isCreated() or !$this->_form->isSent() or !$this->_form->isValid()) {
                                $this->_stepTwo();
                                break;
                            }

                            $this->_activationKey = $this->_model->generateActivationKey();

                            $this->_model->addRegistrationRequest($this->_form->getData('nickname'), $this->_form->getData('password'), $this->_form->getData('email'), $this->_activationKey);

                            $result = $this->_model->sendRegisterEmail($this->_form->getSentData('email'), $this->_form->getSentData('nickname'), $this->_activationKey);

                            if($result === false) {
                                $this->_stepNoEmailSent();
                                break;
                            }

                            $this->_stepThree();

                            break;
                        }

                    case 4:
                        {
                            $this->_stepThree();
                            break;
                        }

                    default:
                        {
                            // should never happen
                            $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array('step' => 2);
                            throw new Chrome_Exception('Undefined step in registration!');
                        }
                }
            }
        } else
            if($this->_requestData->getGET('action') === 'confirm_registration') {

                //if($this->requestData->getGET('activationKey'))
                // validate activation key

                $result = $this->_model->checkRegistration($this->_requestData->getGET('activationKey'));

                if($result === false) {
                    $this->_view->registrationFailed();

                } else {
                    $success = $this->_model->finishRegistration($result['name'], $result['pass'], $result['pw_salt'], $result['email'], $this->_requestData->getGET('activationKey'));


                    // user successfully registered
                    if($success === true) {

                        $this->_view->registrationFinished();

                        // activationKey is invalid
                    } else {

                        $this->_view->registrationFailed();

                    }
                }
            }

        $this->_view->render($this);
    }

    private function _stepOne()
    {
        if($this->_form == null) {
            $this->_form = new Chrome_Form_Register_StepOne();
        }

        if(!$this->_form->isCreated()) {
            $this->_form->create();
        }

        $this->_view->setStepOne();

        $session = Chrome_Session::getInstance();

        $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array('step' => 2);
    }

    private function _stepTwo()
    {
        if(!($this->_form instanceof Chrome_Form_Register_StepTwo)) {
            $this->_form = new Chrome_Form_Register_StepTwo();
        }

        $this->_form->create();


        $this->_view->setStepTwo();

        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 3;
        $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }

    private function _stepThree()
    {
        $this->_view->setStepThree();

        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 4;
        $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }

    private function _stepNoEmailSent()
    {
        $this->_view->setStepNoEmailSent();

        $session = Chrome_Session::getInstance();

        $array = $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 4;
        $session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;

    }

    public function getActivationKey()
    {
        return $this->_activationKey;
    }
}
