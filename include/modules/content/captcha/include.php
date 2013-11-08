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

        //$this->_elements[$this->_id] = new Chrome_Form_Element_Form($this, $this->_id, array(Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME => 300, Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME => 1));
        //$this->_elements[$this->_id]->setDecorator(new Chrome_Form_Decorator_Form_Yaml(array(), array()));

        //$this->_elements['error'] = new Chrome_Form_Element_Error($this, 'error', array(Chrome_Form_Element_Error::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS => array(Chrome_Form_Decorator_Error_Default::CHROME_FORM_DECORATOR_ERROR_DISPLAY_ALL => true)));

        $submitOption = new Chrome_Form_Option_Element();
        $submitOption->setIsRequired(true)->setAllowedValue('test it!');
        $submitElement = new Chrome_Form_Element_Submit($this, 'submit', $submitOption);

        //$this->_addElement($submitElement);

        //$submitButton = new Chrome_Form_Element_Submit($this, 'submit', array(Chrome_Form_Element_Submit::IS_REQUIRED => true, Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES => array('test it!')));

        $buttonsOption = new Chrome_Form_Option_Element_Buttons();
        $buttonsOption->attach($submitElement);
        $buttonsElement = new Chrome_Form_Element_Buttons($this, 'buttons', $buttonsOption);
        $this->_addElement($buttonsElement);
        //$this->_elements['buttons'] = new Chrome_Form_Element_Buttons($this, 'buttons', array(Chrome_Form_Element_Buttons::CHROME_FORM_ELEMENT_BUTTONS => array($submitButton)));

        $captchaOption = new Chrome_Form_Option_Element_Captcha($this);
        #$captchaOption->;#->setFrontendOptions(array(Chrome_Captcha_Interface::CHROME_CAPTCHA_ENGINE => 'recaptcha'));
        $captchaElement = new Chrome_Form_Element_Captcha($this, 'captcha', $captchaOption);
        $this->_addElement($captchaElement);
    /*
        $captchaOption = new Chrome_Form_Option_Element_Captcha($this);
        $captchaElement = new Chrome_Form_Element_Captcha($this, 'captcha', $captchaOption);
        $this->_addElement($captchaElement);
*/
        //$this->_elements['captcha'] = new Chrome_Form_Element_Captcha($this, 'captcha', array(Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array('size' => 30), Chrome_Form_Element_Captcha::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS => array(Chrome_Captcha_Interface::CHROME_CAPTCHA_ENGINE => 'recaptcha')));
    }
}

class Chrome_View_Form_Captcha extends Chrome_View_Form_Abstract
{

}