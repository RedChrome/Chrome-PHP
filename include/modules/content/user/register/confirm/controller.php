<?php

namespace Chrome\Controller\User\Register;

use \Chrome\Controller\AbstractModule;

class Confirm extends AbstractModule
{
    protected $_action = null;

    public function __construct(\Chrome\Action\User\Register\Confirm $action, \Chrome\Interactor\User\Registration $interactor, \Chrome\View\User\Register $view)
    {
        $this->_action = $action;
        $this->_interactor = $interactor;
        $this->_view = $view;
    }

    protected function _execute()
    {
        if(!$this->_action->isSent() || !$this->_action->isValid()) {
            $this->_view->registrationFailed();
            return;
        }

        $result = new \Chrome\Interactor\Result();

        $request = $this->_interactor->getRegistrationRequest($this->_action->getActivationKey(), $result);

        if($result->hasFailed()) {
            $this->_view->registrationFailed();
            return;
        }

        $userModel = $this->_applicationContext->getDiContainer()->get('\Chrome\Model\User\User_Interface');
        $authHelper = $this->_applicationContext->getDiContainer()->get('\Chrome\Helper\Authentication\Creation_Interface');
        $result = new \Chrome\Interactor\Result();

        $this->_interactor->activateRegistrationRequest($request, $userModel, $authHelper, $result);

        if($result->hasFailed())
        {
            $this->_view->registrationFailed();

            var_dump($result);
        } else
        {
            $success = $this->_model->finishRegistration($result['name'], $result['pass'], $result['pw_salt'], $result['email'], $activationKey);

            // user successfully registered
            if($success === true)
            {
                $this->_view->registrationFinished();

            } else // activationKey is invalid
            {
                $this->_view->registrationFailed();
            }
        }
    }
}
