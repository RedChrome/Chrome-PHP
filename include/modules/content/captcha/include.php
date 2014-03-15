<?php
class Chrome_Form_Captcha extends Chrome_Form_Abstract
{
    protected function _init()
    {
        $this->_id = 'Captcha_Test';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

        $formOption = new Chrome_Form_Option_Element_Form($this->_getFormStorage());
        $formOption->setMaxAllowedTime(300)->setMinAllowedTime(1);
        $formElement = new Chrome_Form_Element_Form($this, $this->_id, $formOption);
        $this->_addElement($formElement);

        $submitOption = new Chrome_Form_Option_Element();
        $submitOption->setIsRequired(true)->setAllowedValue('test it!');
        $submitElement = new Chrome_Form_Element_Submit($this, 'submit', $submitOption);

        $buttonsOption = new Chrome_Form_Option_Element_Buttons();
        $buttonsOption->attach($submitElement);
        $buttonsElement = new Chrome_Form_Element_Buttons($this, 'buttons', $buttonsOption);
        $this->_addElement($buttonsElement);

        $captchaOption = new Chrome_Form_Option_Element_Captcha($this);
        $captchaElement = new Chrome_Form_Element_Captcha($this, 'captcha', $captchaOption);
        $this->_addElement($captchaElement);
    }
}
class Chrome_View_Form_Captcha extends Chrome_View_Form_Abstract
{

}