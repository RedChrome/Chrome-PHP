<?php
class Chrome_Form_Register_StepOne extends Chrome_Form_Abstract
{

    protected function _init()
    {
        $this->_id = 'Register_StepOne';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

        $lang = $this->getApplicationContext()->getViewContext()->getLocalization()->getTranslate();
        //$lang = new Chrome_Language('modules/content/user/registration');

        $storageSession = new Chrome_Form_Storage_Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);

        $formElementOption = new Chrome_Form_Option_Element_Form($storageSession);
        $formElementOption->setMaxAllowedTime(300)->setMinAllowedTime(1);
        $this->_addElement(new Chrome_Form_Element_Form($this, $this->_id, $formElementOption));

        // $errorOption = new Chrome_Form_Option_Element();
        // $errorElement = new Chrome_Form_Element_Error($this, 'error', $errorOption);
        // this->_addElement($errorElement);

        $acceptOption = new Chrome_Form_Option_Element_Multiple();
        $acceptOption->setRequired(array('accepted'))->setAllowedValues(array('accepted'));

        $acceptElement = new Chrome_Form_Element_Checkbox($this, 'accept', $acceptOption);
        $this->_addElement($acceptElement);

        $submitOption = new Chrome_Form_Option_Element();
        $submitOption->setAllowedValue($lang->get('register'));

        $submitElement = new Chrome_Form_Element_Submit($this, 'submit', $submitOption);
        $this->_addElement($submitElement);

        $storeHandler = new Chrome_Form_Handler_Store($storageSession, new Chrome_Form_Option_Storage(), array('accept'));
        $this->setAttribute(self::ATTRIBUTE_STORE, $storeHandler);
    }
}
class Chrome_View_Form_Register_StepOne extends Chrome_View_Form_Abstract
{

    protected function _initFactories()
    {
        $this->_formElementFactory = new Chrome_View_Form_Element_Factory_Suffix('Default');
        parent::_initFactories();
    }

    protected function _modifyElementOption(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption)
    {
        $lang = $this->_viewContext->getLocalization()->getTranslate();
        //$lang = new Chrome_Language('modules/content/user/registration');

        switch($formElement->getID())
        {
            case 'accept':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('accepted' => $lang->get('rules_agree'))));
                    // viewOption->setLabelPosition($viewOption::LABEL_POSITION_BEHIND);
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

        $lang = $this->_applicationContext->getViewContext()->getLocalization()->getTranslate();
        //$lang = new Chrome_Language('modules/content/user/registration');

        $emailValidatorDefault = new Chrome_Validator_Email_Default();
        //$emailExistsValidator = new Chrome_Validator_Email_Exists();
        //$emailBlacklistValidator = new Chrome_Validator_Email_Blacklist();
        //$emailExistsValidator->setOptions(array(Chrome_Validator_Email_Exists::CHROME_VALIDATOR_EMAIL_EXISTS_VALID_ON_SUCCESS => false));

        $emailValidator = new Chrome_Validator_Composition_And();
        $emailValidator->addValidators(array($emailValidatorDefault,
                                                //$emailExistsValidator,
                                                //$emailBlacklistValidator
        ));

        $birthdayValidator = new Chrome_Validator_Form_Element_Birthday();

        $passwordValidator = new Chrome_Validator_Form_Password();

        $nicknameValidator = new Chrome_Validator_Form_NicknameRegister();

        $emailConverter = new Chrome_Converter_List();
        $emailConverter->setConversion(array('charToHtml', 'stripHtml', 'strToLower', 'trim'));

        $nameConverter = new Chrome_Converter_List();
        $nameConverter->setConversion(array('charToHtml', 'stripHtml'));

        $storageSession = new Chrome_Form_Storage_Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);
        $formOption = new Chrome_Form_Option_Element_Form($storageSession);
        $formOption->setMinAllowedTime(1)->setMaxAllowedTime(300);

        $formElement = new Chrome_Form_Element_Form($this, $this->_id, $formOption);
        $this->_addElement($formElement);

        $backwardButton = new Chrome_Form_Element_Backward($this, 'backward', new Chrome_Form_Option_Element());

        $submitOption = new Chrome_Form_Option_Element();
        $submitOption->setIsRequired(false)->setAllowedValue($lang->get('register'));
        $submitButton = new Chrome_Form_Element_Submit($this, 'submit', $submitOption);

        $buttonsOption = new Chrome_Form_Option_Element_Buttons();
        $buttonsOption->setIsRequired(true)->setAttachments(array($backwardButton, $submitButton));

        $buttonsElement = new Chrome_Form_Element_Buttons($this, 'buttons', $buttonsOption);
        $this->_addElement($buttonsElement);

        $captchaOption = new Chrome_Form_Option_Element_Captcha($this);
        $captchaElement = new Chrome_Form_Element_Captcha($this, 'captcha', $captchaOption);
        $this->_addElement($captchaElement);

        $birthdayOption = new Chrome_Form_Option_Element();
        $birthdayOption->setIsRequired(true)->setValidator($birthdayValidator);

        $birthdayElement = new Chrome_Form_Element_Date($this, 'birthday', $birthdayOption);
        $this->_addElement($birthdayElement);

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

        $storeHandler = new Chrome_Form_Handler_Store($storageSession, new Chrome_Form_Option_Storage(), array('nickname', 'birthday', 'email'));
        $this->setAttribute(self::ATTRIBUTE_STORE, $storeHandler);
    }
}
class Chrome_View_Form_Register_StepTwo extends Chrome_View_Form_Abstract
{

    protected function _initFactories()
    {
        $this->_formElementFactory = new Chrome_View_Form_Element_Factory_Suffix('Yaml');
        parent::_initFactories();
    }

    protected function _modifyElementOption(Chrome_Form_Element_Basic_Interface $formElement, Chrome_View_Form_Element_Option_Interface $viewOption)
    {
        $lang = $this->_viewContext->getLocalization()->getTranslate();
        //$lang = new Chrome_Language('modules/content/user/registration');

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
            case 'email':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('email' => $lang->get('email'))));
                    break;
                }
            case 'password':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('password' => $lang->get('password'))));
                    break;
                }
            case 'password2':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('password2' => $lang->get('password_confirm'))));
                    break;
                }
            case 'nickname':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('nickname' => $lang->get('nickname'))));
                    break;
                }
            case 'birthday':
                {
                    $viewOption->setLabel(new Chrome_View_Form_Label_Default(array('birthday' => $lang->get('birthday'))));
                    $viewOption->setDefaultInput(new DateTime());
                    break;
                }
            case 'captcha':
                {
                    break;
                }
        }

        return $viewOption;
    }
}
