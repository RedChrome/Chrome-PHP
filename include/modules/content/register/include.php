<?php

class Chrome_Form_Register_StepOne extends Chrome_Form_Abstract
{
	protected function _init()
	{
		$this->_id = 'Register_StepOne';
		$this->setAttribute( self::ATTRIBUTE_NAME, $this->_id );
		$this->setAttribute( self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST );
		$this->setAttribute( self::ATTRIBUTE_ID, $this->_id );

		$lang = new Chrome_Language( 'modules/content/user/registration' );

		$this->_elements[$this->_id] = new Chrome_Form_Element_Form( $this, $this->_id, array( Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME =>
				300, Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME => 1 ) );

		$this->_elements['error'] = new Chrome_Form_Element_Error( $this, 'error', array( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS =>
				array( Chrome_Form_Decorator_Error_Default::CHROME_FORM_DECORATOR_ERROR_EXCLUDE_ELEMENTS =>
					array( 'submit', $this->_id ) ) ) );

		$this->_elements['accept'] = new Chrome_Form_Element_Checkbox( $this, 'accept', array( Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
				Chrome_Form_Element_Checkbox::CHROME_FORM_ELEMENT_SELECTION_OPTIONS => array( 'accepted' ) ) );

		$this->_elements['submit'] = new Chrome_Form_Element_Submit( $this, 'submit', array( Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES =>
				array( $lang->get( 'register' ) ) ) );
	}
}

class Chrome_Form_Register_StepTwo extends Chrome_Form_Abstract
{
	protected function _init()
	{
		$this->_id = 'Register_StepTwo';
		$this->setAttribute( self::ATTRIBUTE_NAME, $this->_id );
		$this->setAttribute( self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST );
		$this->setAttribute( self::ATTRIBUTE_ID, $this->_id );

		$lang = new Chrome_Language( 'modules/content/user/registration' );

		$emailValidator = new Chrome_Validator_Email_Default();
        $emailExistsValidator = new Chrome_Validator_Email_Exists();
        $emailBlacklistValidator = new Chrome_Validator_Email_Blacklist();
        $emailExistsValidator->setOptions(array(Chrome_Validator_Email_Exists::CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS => false));

		$passwordValidator = new Chrome_Validator_Form_Password();

		$nicknameValidator = new Chrome_Validator_Form_NicknameRegister();

		$emailConverter = new Chrome_Converter_Value();
		$emailConverter->addFilter( 'escape' );
		$emailConverter->addFilter( 'convert_char_to_html' );
		$emailConverter->addFilter( 'stripHTML' );
        $emailConverter->addFilter( 'strToLower');

        $nameConverter = new Chrome_Converter_Value();
		$nameConverter->addFilter( 'escape' );
		$nameConverter->addFilter( 'convert_char_to_html' );
		$nameConverter->addFilter( 'stripHTML' );


		$this->_elements[$this->_id] = new Chrome_Form_Element_Form( $this, $this->_id, array( Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MAX_ALLOWED_TIME =>
				300, Chrome_Form_Element_Form::CHROME_FORM_ELEMENT_FORM_MIN_ALLOWED_TIME => 1 ) );


		$this->_elements['error'] = new Chrome_Form_Element_Error( $this, 'error', array() );


         // with the comment you can enable/disable the auto deletion of password content, if the user clicks on backward
        $backwardButton = new Chrome_Form_Element_Backward( $this, 'backward', //array( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS =>
				//array( Chrome_Form_Decorator_Backward_Default::CHROME_FORM_DECORATOR_BACKWARD_DELETE_PASSWORDS => true ) )
                array() );

		$submitButton = new Chrome_Form_Element_Submit( $this, 'submit', array( Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
				Chrome_Form_Element_Submit::CHROME_FORM_ELEMENT_SUBMIT_VALUES => array( $lang->get( 'register' ) ) ) );

        $this->_elements['buttons'] = new Chrome_Form_Element_Buttons($this, 'buttons', array(Chrome_Form_Element_Buttons::CHROME_FORM_ELEMENT_BUTTONS => array($submitButton, $backwardButton)));

		$this->_elements['captcha'] = new Chrome_Form_Element_Captcha( $this, 'captcha', array( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES =>
				array( 'size' => 30 ) ) );

		$this->_elements['birthday'] = new Chrome_Form_Element_Birthday( $this, 'birthday', array( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_NOT_SAVE_NULL_DATA => true ) );

		$this->_elements['email'] = new Chrome_Form_Element_Text( $this, 'email', array(
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => array( $emailValidator, $emailExistsValidator, $emailBlacklistValidator ),
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array( 'size' => 30 ),
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE => array( $emailConverter  ) ) );

		$this->_elements['password'] = new Chrome_Form_Element_Password( $this, 'password', array(
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => array( $passwordValidator ),
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array( 'size' => 30 ) ) );


		// add a validator, to check whether the pws are the same or not
		$this->_elements['password2'] = new Chrome_Form_Element_Password( $this, 'password2', array(
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => array( $passwordValidator ),
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array( 'size' => 30 ) ) );


		// add nickname validator
		$this->_elements['nickname'] = new Chrome_Form_Element_Text( $this, 'nickname', array(
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_IS_REQUIRED => true,
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_VALIDATOR_NAMESPACE => array( $nicknameValidator ),
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array( 'size' => 30 ),
			Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_CONVERTER_NAMESPACE => array( $nameConverter ) ) );
	}
}
