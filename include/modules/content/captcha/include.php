<?php

class Chrome_Form_Captcha extends Chrome_Form_Abstract
{
	protected function _init()
	{
		$this->_id = 'Captcha_Test';
		$this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
		$this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
		$this->setAttribute(self::ATTRIBUTE_ID, $this->_id);


		$this->_elements[$this->_id] = new Chrome_Form_Element_Form($this, $this->_id, array(Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME => 300, Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME => 1));
		//$this->_elements[$this->_id]->setDecorator(new Chrome_Form_Decorator_Form_Yaml(array(), array()));

		$this->_elements['error'] = new Chrome_Form_Element_Error($this, 'error', array(Chrome_Form_Element_Error::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS => array(Chrome_Form_Decorator_Error_Default::CHROME_FORM_DECORATOR_ERROR_DISPLAY_ALL => true)));


		$submitButton = new Chrome_Form_Element_Submit($this, 'submit', array(Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_IS_REQUIRED => true, Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES => array('test it!')));


		$this->_elements['buttons'] = new Chrome_Form_Element_Buttons($this, 'buttons', array(Chrome_Form_Element_Buttons::CHROME_FORM_ELEMENT_BUTTONS => array($submitButton)));

		$this->_elements['captcha'] = new Chrome_Form_Element_Captcha($this, 'captcha', array(Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array('size' => 30), Chrome_Form_Element_Captcha::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS => array(Chrome_Captcha_Interface::CHROME_CAPTCHA_ENGINE => 'recaptcha')));

	}
}
