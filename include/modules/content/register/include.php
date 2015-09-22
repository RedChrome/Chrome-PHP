<?php

/**
 * CHROME-PHP CMS
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @package    CHROME-PHP
 * @subpackage Chrome.View.User
 */

namespace Chrome\Form\Module\User\Register;

use \Chrome\Validator\General\Password\PasswordValidator;
use \Chrome\Validator\User\NicknameValidator;
use \Chrome\Validator\Form\Element\YearBirthdayValidator;

class StepOne extends \Chrome\Form\AbstractForm
{
    protected function _init()
    {
        $this->_id = 'Register_StepOne';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

        #$lang = $this->getApplicationContext()->getViewContext()->getLocalization()->getTranslate();
        //$lang = new Chrome_Language('modules/content/user/registration');

        $storageSession = new \Chrome\Form\Storage\Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);

        $formElementOption = new \Chrome\Form\Option\Element\Form($storageSession);
        $formElementOption->setMaxAllowedTime(300)->setMinAllowedTime(1);
        $this->_addElement(new \Chrome\Form\Element\Form($this, $this->_id, $formElementOption));

        // $errorOption = new \Chrome\Form\Option\Element();
        // $errorElement = new Chrome_Form_Element_Error($this, 'error', $errorOption);
        // this->_addElement($errorElement);

        $acceptOption = new \Chrome\Form\Option\MultipleElement();
        $acceptOption->setRequired(array('accepted'))->setAllowedValues(array('accepted'));

        $acceptElement = new \Chrome\Form\Element\Checkbox($this, 'accept', $acceptOption);
        $this->_addElement($acceptElement);

        $submitOption = new \Chrome\Form\Option\Element();
        #$submitOption->setAllowedValue($lang->get('register'));

        $submitElement = new \Chrome\Form\Element\Submit($this, 'submit', $submitOption);

        $buttonsOption = new \Chrome\Form\Option\Element\Buttons();
        $buttonsOption->attach($submitElement);

        $buttonsElement = new \Chrome\Form\Element\Buttons($this, 'buttons', $buttonsOption);
        $this->_addElement($buttonsElement);

        $storeHandler = new \Chrome\Form\Handler\Store($storageSession, new \Chrome\Form\Option\Storage(), array('accept'));
        $this->setAttribute(self::ATTRIBUTE_STORE, $storeHandler);
    }
}

class StepTwo extends \Chrome\Form\AbstractForm
{
    protected function _init()
    {
        $this->_id = 'Register_StepTwo';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

        #$lang = $this->_applicationContext->getViewContext()->getLocalization()->getTranslate();
        //$lang = new Chrome_Language('modules/content/user/registration');

        $emailSyntaxValidator = new \Chrome\Validator\Email\SyntaxValidator();

        $emailValidator = new \Chrome\Validator\Composition\AndComposition();
        $emailValidator->addValidators(array($emailSyntaxValidator,
            //$emailExistsValidator,
            //$emailBlacklistValidator
        ));

        $birthdayValidator = new YearBirthdayValidator();
        $passwordValidator = new PasswordValidator();
        $nicknameValidator = new NicknameValidator();

        $emailConverter = new \Chrome\Converter\ConverterList();
        $emailConverter->setConversion(array('charToHtml', 'stripHtml', 'strToLower', 'trim'));

        $nameConverter = new \Chrome\Converter\ConverterList();
        $nameConverter->setConversion(array('charToHtml', 'stripHtml'));

        $storageSession = new \Chrome\Form\Storage\Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);
        $formOption = new \Chrome\Form\Option\Element\Form($storageSession);
        $formOption->setMinAllowedTime(1)->setMaxAllowedTime(300);

        $formElement = new \Chrome\Form\Element\Form($this, $this->_id, $formOption);
        $this->_addElement($formElement);

        $backwardButton = new \Chrome\Form\Element\Backward($this, 'backward', new \Chrome\Form\Option\Element());

        $submitOption = new \Chrome\Form\Option\Element();
        $submitOption->setIsRequired(false);#->setAllowedValue($lang->get('register'));
        $submitButton = new \Chrome\Form\Element\Submit($this, 'submit', $submitOption);

        $buttonsOption = new \Chrome\Form\Option\Element\Buttons();
        $buttonsOption->setIsRequired(true)->setAttachments(array($backwardButton, $submitButton));

        $buttonsElement = new \Chrome\Form\Element\Buttons($this, 'buttons', $buttonsOption);
        $this->_addElement($buttonsElement);

        $captchaOption = new \Chrome\Form\Option\Element\Captcha($this);
        #$captchaOption->setFrontendOptions(array(\Chrome\Captcha\Captcha_Interface::CHROME_CAPTCHA_ENGINE => 'reCaptcha'));
        $captchaElement = new \Chrome\Form\Element\Captcha($this, 'captcha', $captchaOption);
        $this->_addElement($captchaElement);

        $birthdayOption = new \Chrome\Form\Option\Element();
        $birthdayOption->setIsRequired(true)->setValidator($birthdayValidator);

        $birthdayElement = new \Chrome\Form\Element\Date($this, 'birthday', $birthdayOption);
        $this->_addElement($birthdayElement);

        $emailOption = new \Chrome\Form\Option\Element();
        $emailOption->setIsRequired(true)->setConversion($emailConverter)->setValidator($emailValidator);

        $emailElement = new \Chrome\Form\Element\Text($this, 'email', $emailOption);
        $this->_addElement($emailElement);

        $passwordOption = new \Chrome\Form\Option\Element();
        $passwordOption->setIsRequired(true)->setValidator($passwordValidator);

        $passwordElement = new \Chrome\Form\Element\Password($this, 'password', $passwordOption);
        $passwordElementSecond = new \Chrome\Form\Element\Password($this, 'password2', $passwordOption);

        $this->_addElement($passwordElement);
        $this->_addElement($passwordElementSecond);

        $nicknameOption = new \Chrome\Form\Option\Element();
        $nicknameOption->setIsRequired(true)->setValidator($nicknameValidator)->setConversion($nameConverter);

        $nicknameElement = new \Chrome\Form\Element\Text($this, 'nickname', $nicknameOption);
        $this->_addElement($nicknameElement);

        $storeHandler = new \Chrome\Form\Handler\Store($storageSession, new \Chrome\Form\Option\Storage(), array('nickname', 'birthday', 'email'));
        $this->setAttribute(self::ATTRIBUTE_STORE, $storeHandler);
    }
}

namespace Chrome\View\Form\Module\User\Register;

class StepOne extends \Chrome\View\Form\AbstractForm
{
    protected function _initFactories()
    {
        // TODO: inject this class

        #$this->_formElementFactory = new Chrome_View_Form_Element_Factory_Yaml();
        parent::_initFactories();
    }

    protected function _modifyElementOption(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewOption)
    {
        $lang = $this->_viewContext->getLocalization()->getTranslate();
        //$lang = new Chrome_Language('modules/content/user/registration');

        switch($formElement->getID())
        {
            case 'accept':
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('accepted' => $lang->get('rules_agree'))));
                    break;
                    // viewOption->setLabelPosition($viewOption::LABEL_POSITION_BEHIND);
                }
            case 'submit':
                {
                    $formElement->getOption()->setAllowedValue($lang->get('register'));
                }
        }

        return $viewOption;
    }
}


class StepTwo extends \Chrome\View\Form\AbstractForm
{
    protected function _modifyElementOption(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewOption)
    {
        $lang = $this->_viewContext->getLocalization()->getTranslate();
        //$lang = new Chrome_Language('modules/content/user/registration');

        switch($formElement->getID())
        {
            case 'submit':
                {
                    $formElement->getOption()->setAllowedValue($lang->get('register'));
                    break;
                    // viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('accepted' => $lang->get('rules_agree'))));
                }
            case 'backward':
                {
                    // viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('accepted' => $lang->get('rules_agree'))));
                    break;
                }
            case 'email':
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('email' => $lang->get('email'))));
                    break;
                }
            case 'password':
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('password' => $lang->get('password'))));
                    break;
                }
            case 'password2':
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('password2' => $lang->get('password_confirm'))));
                    break;
                }
            case 'nickname':
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('nickname' => $lang->get('nickname'))));
                    break;
                }
            case 'birthday':
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('birthday' => $lang->get('birthday'))));
                    $viewOption->setDefaultInput(new \DateTime());
                    break;
                }
            case 'captcha':
                {
                    #$viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('captcha' => $lang->get('captcha'))));
                    break;
                }
        }

        return $viewOption;
    }
}
