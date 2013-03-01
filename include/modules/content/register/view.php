<?php

class Chrome_View_Register extends Chrome_View_Strategy_Abstract
{
    public function __construct(Chrome_Controller_Abstract $controller) {
        $this->addTitle('Registrieren');
        parent::__construct($controller);
    }

    public function setStepOne() {

        $this->_views[] = new Chrome_View_Register_StepOne($this->_controller);
    }

    public function setStepTwo() {

        $this->_views[] = new Chrome_View_Register_StepTwo($this->_controller);
    }

    public function setStepThree() {
        $this->_views[] = new Chrome_View_Register_StepThree($this->_controller);
    }

    public function setStepNoEmailSent() {
       $this->_views[] = new Chrome_View_Register_StepEmailNotSent($this->_controller);
    }

    public function alreadyRegistered() {
        $this->_views[] = new Chrome_View_Register_AlreadyRegistered($this->_controller);
    }

    public function registrationFinished() {
        $this->addTitle('Fertig');
        $this->_views[] = new Chrome_View_Register_Registration_Finished($this->_controller);
    }

    public function registrationFailed() {
        $this->addTitle('Fehlgeschlagen');
        $this->_views[] = new Chrome_View_Register_Registration_Failed($this->_controller);
    }
}

class Chrome_View_Register_StepOne extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/register/stepOne');
        $template->assign('LANG', new Chrome_Language('modules/content/user/registration'));
        $template->assign('FORM', $this->_controller->getForm());
        return $template->render();

    }
}

class Chrome_View_Register_StepTwo extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/register/stepTwo');
        $template->assign('FORM', $this->_controller->getForm());
        $template->assign('LANG', new Chrome_Language('modules/content/user/registration'));
        return $template->render();
    }
}

class Chrome_View_Register_StepThree extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/register/stepThree');
        return $template->render();
    }
}

class Chrome_View_Register_AlreadyRegistered extends Chrome_View_Abstract
{
    public function render(Chrome_Controller_Interface $controller) {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/register/alreadyRegistered');
        return $template->render();
    }
}

class Chrome_View_Register_Registration_Finished extends Chrome_View_Abstract {
    public function render(Chrome_Controller_Interface $controller) {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/register/registrationFinished');
        return $template->render();
    }
}

class Chrome_View_Register_Registration_Failed extends Chrome_View_Abstract {
    public function render(Chrome_Controller_Interface $controller) {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/register/registrationFailed');
        return $template->render();
    }
}

class Chrome_View_Register_StepEmailNotSent extends Chrome_View_Abstract {
    public function render(Chrome_Controller_Interface $controller) {

        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/register/emailNotSent');
        $template->assign('activationKey', $this->_controller->getActivationKey());
        return $template->render();

    }
}