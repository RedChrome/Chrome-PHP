<?php

class Chrome_View_Register extends NEWChrome_View_Strategy_Abstract
{
    protected $_form = null;

    protected function _setUp()
    {
        $this->addTitle('Registrieren');
    }

    public function setStepOne(Chrome_Form_Interface $form)
    {
        $this->_views[] = new Chrome_View_Register_StepOne_Renderer_Template_StepOne(new Chrome_View_Form_Register_StepOne($form, $this->_viewContext));
    }

    public function setStepTwo(Chrome_Form_Interface $form)
    {
        $formView = new Chrome_View_Form_Register_StepTwo($form, $this->_viewContext);
        $this->_views[] = new Chrome_View_Register_Form_Renderer_Template_StepTwo($formView);
        #$this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Register_StepTwo', $this->_controller);
    }

    public function setStepThree()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Register_StepThree');
    }

    public function setStepNoEmailSent()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Register_StepEmailNotSent');
    }

    public function alreadyRegistered()
    {
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Register_AlreadyRegistered');
    }

    public function registrationFinished()
    {
        $this->addTitle('Fertig');
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Register_Registration_Finished');
    }

    public function registrationFailed()
    {
        $this->addTitle('Fehlgeschlagen');
        $this->_views[] = $this->_viewContext->getFactory()->build('Chrome_View_Register_Registration_Failed');
    }
}

class Chrome_View_Register_StepOne_Renderer_Template_StepOne extends Chrome_View_Form_Renderer_Template_Simple_Abstract
{
    protected $_templateFile = 'modules/content/register/stepOne';
}

class Chrome_View_Register_Form_Renderer_Template_StepTwo extends Chrome_View_Form_Renderer_Template_Simple_Abstract
{
    protected $_templateFile = 'modules/content/register/stepTwo';
}

class Chrome_View_Register_StepThree extends Chrome_View_Template_Simple_Abstract
{
    protected $_templateFile = 'modules/content/register/stepThree';
}

class Chrome_View_Register_AlreadyRegistered extends Chrome_View_Template_Simple_Abstract
{
    protected $_templateFile = 'modules/content/register/alreadyRegistered';
}

class Chrome_View_Register_Registration_Finished extends Chrome_View_Template_Simple_Abstract
{
    protected $_templateFile = 'modules/content/register/registrationFinished';
}

class Chrome_View_Register_Registration_Failed extends Chrome_View_Template_Simple_Abstract
{
    protected $_templateFile = 'modules/content/register/registrationFailed';
}

class Chrome_View_Register_StepEmailNotSent extends Chrome_View_Abstract
{
    public function render()
    {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/register/emailNotSent');
        $template->assign('activationKey', $this->_controller->getActivationKey());
        return $template->render();
    }
}