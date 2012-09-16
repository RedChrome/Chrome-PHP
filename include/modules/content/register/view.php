<?php

class Chrome_View_Register extends Chrome_View_Abstract
{
    public function _preConstruct() {
        $this->addTitle('Registrieren');
    }

    public function setStepOne() {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Register_StepOne($this->_controller));
    }

    public function setStepTwo() {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Register_StepTwo($this->_controller));
    }

    public function setStepThree() {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Register_StepThree($this->_controller));
    }

    public function alreadyRegistered() {
        Chrome_Design_Composite_Content::getInstance()->getComposite()->addView(new Chrome_View_Register_AlreadyRegistered($this->_controller));
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