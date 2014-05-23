<?php

namespace Chrome\Controller\User;

use \Chrome\Controller\AbstractModule;

require_once 'view.php';
require_once 'include.php';

class Register extends AbstractModule
{
    const CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE = 'REGISTER';
    protected $_session;
    protected $_authorisation = null;

    public function __construct(\Chrome\Context\Application_Interface $appContext, \Chrome\Interactor\User\Registration $interactor, \Chrome_View_Register $view)
    {
        $this->_applicationContext = $appContext;
        $this->_setRequestHandler($appContext->getRequestHandler());
        $this->_interactor = $interactor;
        $this->_view = $view;
    }

    protected function _execute()
    {
        $authorisation = $this->_applicationContext->getAuthorisation();

        if($authorisation->isAllowed(new \Chrome\Authorisation\Resource\Resource(new \Chrome\Resource\Resource('register'), 'register')) === false)
        {
            $this->_view->alreadyRegistered();
            return;
        }

        $this->_session = $this->_requestHandler->getRequestData()->getSession();

        if($this->_requestData->getGETData('action') === 'register')
        {
            $this->_handleRegisterAction();

        } else if($this->_requestData->getGET('action') === 'confirm_registration')
        {
            $this->_handleRegisterConfirmAction();
        }
    }

    protected function _handleRegisterConfirmAction()
    {
        // if($this->requestData->getGET('activationKey'))
        // validate activation key

        $result = $this->_model->checkRegistration($this->_requestData->getGET('activationKey'));

        if($result === false)
        {
            $this->_view->registrationFailed();
        } else
        {
            $success = $this->_model->finishRegistration($result['name'], $result['pass'], $result['pw_salt'], $result['email'], $this->_requestData->getGET('activationKey'));

            // user successfully registered
            if($success === true)
            {

                $this->_view->registrationFinished();

                // activationKey is invalid
            } else
            {

                $this->_view->registrationFailed();
            }
        }
    }

    protected function _handleRegisterAction()
    {
        if(!isset($this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE]))
        {
            $this->_stepOne();
            return;
        }

        switch($this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE]['step'])
        {
            case 2:
                {
                    $this->_form = new \Chrome_Form_Register_StepOne($this->_applicationContext);

                    if(!$this->_form->isCreated() or !$this->_form->isSent() or !$this->_form->isValid())
                    {

                        if(!$this->_form->isValid())
                        {
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
                    $this->_form = new \Chrome_Form_Register_StepTwo($this->_applicationContext);

                    $data = $this->_form->getData();

                    // go one step back
                    if($this->_form->isSent('buttons') and isset($data['buttons']['backward']))
                    {
                        $this->_form = new \Chrome_Form_Register_StepOne($this->_applicationContext);
                        $this->_form->create();
                        $this->_stepOne();
                        break;
                    }

                    // process the errors
                    if(!$this->_form->isCreated() or !$this->_form->isSent() or !$this->_form->isValid())
                    {
                        $this->_stepTwo();
                        break;
                    }

                    $registrationRequest = new \Chrome\Model\User\Registration\Request();
                    $registrationRequest->setEmail($this->_form->getSentData('email'))
                                        ->setName($this->_form->getSentData('nickname'))
                                        ->setPassword($this->_form->getSentData('password'));

                    $result = new \Chrome\Interactor\Result();

                    $this->_interactor->addRegistrationRequest($registrationRequest, $result);

                    /*
                         $this->_activationKey = $this->_model->generateActivationKey();

                        $this->_model->addRegistrationRequest($this->_form->getData('nickname'), $this->_form->getData('password'), $this->_form->getData('email'), $this->_activationKey);

                        $result = $this->_model->sendRegisterEmail($this->_form->getSentData('email'), $this->_form->getSentData('nickname'), $this->_activationKey);

                        if($result === false)
                        {
                        $this->_stepNoEmailSent();
                        break;
                        }
                    */
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
                    $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array('step' => 2);
                    throw new \Chrome\Exception('Undefined step in registration!');
                }
        }
    }

    private function _stepOne()
    {
        if($this->_form == null)
        {
            $this->_form = new \Chrome_Form_Register_StepOne($this->_applicationContext);
        }

        if(!$this->_form->isCreated())
        {
            $this->_form->create();
        }

        $this->_view->setStepOne($this->_form);

        $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = array('step' => 2);
    }

    private function _stepTwo()
    {
        if(!($this->_form instanceof Chrome_Form_Register_StepTwo))
        {
            $this->_form = new \Chrome_Form_Register_StepTwo($this->_applicationContext);
        }

        $this->_form->create();

        $this->_view->setStepTwo($this->_form, $this->_applicationContext->getDiContainer()->get('\Chrome\View\Form\Element\Factory\Default'));

        $array = $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 3;
        $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }

    private function _stepThree()
    {
        $this->_view->setStepThree();
        $array = $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 4;
        $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }

    private function _stepNoEmailSent()
    {
        $this->_view->setStepNoEmailSent();
        $array = $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE];
        $array['step'] = 4;
        $this->_session[self::CHROME_CONTROLLER_REGISTER_SESSION_NAMESPACE] = $array;
    }
}
