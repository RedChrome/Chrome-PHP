<?php

class Chrome_View_Captcha extends Chrome_View_Strategy_Abstract
{
    public function _setUp()
    {
        $this->addTitle('Captcha Test');
    }

    public function test(Chrome_Form_Interface $form, Chrome_View_Form_Element_Factory_Interface $elementFactory)
    {
        $viewForm = new Chrome_View_Form_Captcha($form, $this->_viewContext);
        $viewForm->setElementFactory($elementFactory);
        $this->_views[] = new Chrome_View_Form_Renderer_Captcha($viewForm);
        #$this->_views[] = new Chrome_View_Captcha_Template($this->_viewContext, $this->_controller);
    }

    public function formValid(Chrome_Form_Interface $form)
    {
        // this is needed, because even if the captcha was valid, we want to display a new captcha!
        // normaly, after the captcha is valid, we do not display a captcha again.
        $captcha = $form->getElements('captcha')->getOption()->getCaptcha();
        $captcha->create();

        $this->_views[] = new Chrome_View_Captcha_Template_Success($this->_viewContext);
    }
}

class Chrome_View_Captcha_Template_Success extends Chrome_View
{
    public function render()
    {
        return 'Captcha correctly filled!';
    }
}

class Chrome_View_Form_Renderer_Captcha extends Chrome_View_Form_Renderer_Template_Abstract
{
    protected function _getTemplate()
    {
        $template = new Chrome_Template();
        $template->assignTemplate('modules/content/captcha/captcha_test');
        return $template;
    }
}