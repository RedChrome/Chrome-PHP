<?php
class Chrome_Form_Register_StepOne extends Chrome_Form_Abstract
{

    protected function _init()
    {
        $this->_id = 'Register_StepOne';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

        $lang = new Chrome_Language('modules/content/user/registration');

        $storageSession = new Chrome_Form_Storage_Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);

        $formElementOption = new Chrome_Form_Option_Element_Form($storageSession);
        $formElementOption->setMaxAllowedTime(300)->setMinAllowedTime(1);
        $this->_addElement(new Chrome_Form_Element_Form($this, $this->_id, $formElementOption));

        // $errorOption = new Chrome_Form_Option_Element();
        // $errorElement = new Chrome_Form_Element_Error($this, 'error', $errorOption);
        // this->_addElement($errorElement);

        $acceptOption = new Chrome_Form_Option_Element_Multiple();
        $acceptOption->setIsRequired(true)->setAllowedValues(array('accepted'));

        $acceptElement = new Chrome_Form_Element_Checkbox($this, 'accept', $acceptOption);
        $this->_addElement($acceptElement);

        $submitOption = new Chrome_Form_Option_Element_Values();
        $submitOption->setAllowedValues(array($lang->get('register')));

        $submitElement = new Chrome_Form_Element_Submit($this, 'submit', $submitOption);
        $this->_addElement($submitElement);

        $storeHandler = new Chrome_Form_Handler_Store($storageSession, new Chrome_Form_Option_Storage(), array('accept'));
        $this->addReceivingHandler($storeHandler);
    }
}
class Chrome_View_Form_Register_StepOne extends Chrome_View_Form_Abstract
{

    protected function _initFactories()
    {
        $this->_formElementFactory = new Chrome_View_Form_Element_Factory_Suffix('Default');
        parent::_initFactories();
    }

    protected function _modifyElementOption(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption)
    {
        $lang = new Chrome_Language('modules/content/user/registration');

        switch($formElement->getID())
        {
            case 'accept':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('accepted' => $lang->get('rules_agree'))));
                    $viewOption->setLabelPosition($viewOption::LABEL_POSITION_BEHIND);
                }
        }

        return $viewOption;
    }
}
class Chrome_Form_Register_StepTwo extends Chrome_Form_Abstract
{

    protected function _init()
    {
        $this->_id = 'Register_StepTwo';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

        $lang = new Chrome_Language('modules/content/user/registration');

        $emailValidatorDefault = new Chrome_Validator_Email_Default();
        $emailExistsValidator = new Chrome_Validator_Email_Exists();
        $emailBlacklistValidator = new Chrome_Validator_Email_Blacklist();
        $emailExistsValidator->setOptions(array(Chrome_Validator_Email_Exists::CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS => false));

        $emailValidator = new Chrome_Validator_Composition_And();
        $emailValidator->addValidators(array($emailValidatorDefault, $emailExistsValidator, $emailBlacklistValidator));

        $passwordValidator = new Chrome_Validator_Form_Password();

        $nicknameValidator = new Chrome_Validator_Form_NicknameRegister();

        $emailConverter = new Chrome_Converter_List();
        $emailConverter->setConversion(array('charToHtml', 'stripHtml', 'strToLower'));

        $nameConverter = new Chrome_Converter_List();
        $nameConverter->setConversion(array('charToHtml', 'stripHtml'));

        $formOption = new Chrome_Form_Option_Element_Form(new Chrome_Form_Storage_Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id));
        $formOption->setMinAllowedTime(1)->setMaxAllowedTime(300);

        $formElement = new Chrome_Form_Element_Form($this, $this->_id, $formOption);
        $this->_addElement($formElement);

        // $errorOption = new Chrome_Form_Option_Element();
        // $errorElement = new Chrome_Form_Element_Error($this, 'error', $errorOption);
        // $this->_addElement($errorElement);

        $backwardButton = new Chrome_Form_Element_Backward($this, 'backward', new Chrome_Form_Option_Element());

        $submitOption = new Chrome_Form_Option_Element_Values();
        $submitOption->setIsRequired(true)->setAllowedValues(array($lang->get('register')));
        $submitButton = new Chrome_Form_Element_Submit($this, 'submit', $submitOption);

        $buttonsOption = new Chrome_Form_Option_Element_Buttons();
        $buttonsOption->setIsRequired(true)->setAttachments(array($submitButton, $backwardButton));

        $buttonsElement = new Chrome_Form_Element_Buttons($this, 'buttons', $buttonsOption);
        $this->_addElement($buttonsElement);

        /*
         * @todo: reimplement captcha and birthday $this->_elements['captcha'] = new Chrome_Form_Element_Captcha( $this, 'captcha', array( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_ATTRIBUTES => array( 'size' => 30 ), //Chrome_Form_Element_Captcha::CHROME_FORM_ELEMENT_CAPTCHA_FRONTEND_OPTIONS => array( // Chrome_Captcha_Interface::CHROME_CAPTCHA_ENGINE => 'default'), Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS => array(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL => $lang->get('captcha')), ) ); $this->_elements['birthday'] = new Chrome_Form_Element_Birthday( $this, 'birthday', array( Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_NOT_SAVE_NULL_DATA => true, Chrome_Form_Element_Abstract::CHROME_FORM_ELEMENT_DECORATOR_OPTIONS => array( Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL => $lang->get('birthday')), ) );
         */

        $emailOption = new Chrome_Form_Option_Element();
        $emailOption->setIsRequired(true)->setConversion($emailConverter)->setValidator($emailValidator);

        $emailElement = new Chrome_Form_Element_Text($this, 'email', $emailOption);
        $this->_addElement($emailElement);

        $passwordOption = new Chrome_Form_Option_Element();
        $passwordOption->setIsRequired(true)->setValidator($passwordValidator);

        $passwordElement = new Chrome_Form_Element_Password($this, 'password', $passwordOption);
        $passwordElement2 = new Chrome_Form_Element_Password($this, 'password2', $passwordOption);

        $this->_addElement($passwordElement);
        $this->_addElement($passwordElement2);

        $nicknameOption = new Chrome_Form_Option_Element();
        $nicknameOption->setIsRequired(true)->setValidator($nicknameValidator)->setConversion($nameConverter);

        $nicknameElement = new Chrome_Form_Element_Text($this, 'nickname', $nicknameOption);
        $this->_addElement($nicknameElement);
    }
}
class Chrome_View_Form_Register_StepTwo extends Chrome_View_Form_Abstract
{

    protected function _initFactories()
    {
        $this->_formElementFactory = new Chrome_View_Form_Element_Factory_Suffix('Default');
        parent::_initFactories();
    }

    protected function _modifyElementOption(Chrome_Form_Element_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption)
    {
        $lang = new Chrome_Language('modules/content/user/registration');

        switch($formElement->getID())
        {
            case 'submit':
                {
                    // viewOption->setLabel(new Chrome_View_Form_Label_Default(array('accepted' => $lang->get('rules_agree'))));
                    break;
                }
            case 'backward':
                {
                    // viewOption->setLabel(new Chrome_View_Form_Label_Default(array('accepted' => $lang->get('rules_agree'))));
                    break;
                }
        }

        return $viewOption;
    }
}
