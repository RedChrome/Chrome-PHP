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
    const
        ELEMENT_ACCEPT = 'accept',
        ELEMENT_SUBMIT = 'submit',
        ELEMENT_BUTTONS = 'buttons';

    protected function _init()
    {
        $this->_id = 'Register_StepOne';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

        $storageSession = new \Chrome\Form\Storage\Session($this->_applicationContext->getRequestHandler()->getRequestData()->getSession(), $this->_id);

        $formElementOption = new \Chrome\Form\Option\Element\Form($storageSession);
        $formElementOption->setMaxAllowedTime(300)->setMinAllowedTime(1);
        $this->_addElement(new \Chrome\Form\Element\Form($this, $this->_id, $formElementOption));

        $acceptOption = new \Chrome\Form\Option\MultipleElement();
        $acceptOption->setAllowedValues(array('accepted'))->setRequired(array('accepted'));

        $acceptElement = new \Chrome\Form\Element\Checkbox($this, self::ELEMENT_ACCEPT, $acceptOption);
        $this->_addElement($acceptElement);

        $submitOption = new \Chrome\Form\Option\Element();
        $submitElement = new \Chrome\Form\Element\Submit($this, self::ELEMENT_SUBMIT, $submitOption);

        $buttonsOption = new \Chrome\Form\Option\Element\Buttons();
        $buttonsOption->attach($submitElement);

        $buttonsElement = new \Chrome\Form\Element\Buttons($this, self::ELEMENT_BUTTONS, $buttonsOption);
        $this->_addElement($buttonsElement);

        $storeHandler = new \Chrome\Form\Handler\Store($storageSession, new \Chrome\Form\Option\Storage(), array(self::ELEMENT_ACCEPT));
        $this->setAttribute(self::ATTRIBUTE_STORE, $storeHandler);
    }
}

class StepTwo extends \Chrome\Form\AbstractForm
{
    const
        ELEMENT_BACKWARD = 'backward',
        ELEMENT_SUBMIT = 'submit',
        ELEMENT_BUTTONS = 'buttons',
        ELEMENT_CAPTCHA = 'captcha',
        ELEMENT_BIRTHDAY = 'birthday',
        ELEMENT_EMAIL = 'email',
        ELEMENT_PASSWORD = 'password',
        ELEMENT_PASSWORD_2 = 'password2',
        ELEMENT_NICKNAME = 'nickname';

    protected function _init()
    {
        $this->_id = 'Register_StepTwo';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

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

        $backwardButton = new \Chrome\Form\Element\Backward($this, self::ELEMENT_BACKWARD, new \Chrome\Form\Option\Element());

        $submitOption = new \Chrome\Form\Option\Element();
        $submitOption->setIsRequired(false);#->setAllowedValue($lang->get('register'));
        $submitButton = new \Chrome\Form\Element\Submit($this, self::ELEMENT_SUBMIT, $submitOption);

        $buttonsOption = new \Chrome\Form\Option\Element\Buttons();
        $buttonsOption->setIsRequired(true)->setAttachments(array($backwardButton, $submitButton));

        $buttonsElement = new \Chrome\Form\Element\Buttons($this, self::ELEMENT_BUTTONS, $buttonsOption);
        $this->_addElement($buttonsElement);

        $captchaOption = new \Chrome\Form\Option\Element\Captcha($this);
        #$captchaOption->setFrontendOptions(array(\Chrome\Captcha\Captcha_Interface::CHROME_CAPTCHA_ENGINE => 'reCaptcha'));
        $captchaElement = new \Chrome\Form\Element\Captcha($this, self::ELEMENT_CAPTCHA, $captchaOption);
        $this->_addElement($captchaElement);

        $birthdayOption = new \Chrome\Form\Option\Element();
        $birthdayOption->setIsRequired(true)->setValidator($birthdayValidator);

        $birthdayElement = new \Chrome\Form\Element\Date($this, self::ELEMENT_BIRTHDAY, $birthdayOption);
        $this->_addElement($birthdayElement);

        $emailOption = new \Chrome\Form\Option\Element();
        $emailOption->setIsRequired(true)->setConversion($emailConverter)->setValidator($emailValidator);

        $emailElement = new \Chrome\Form\Element\Text($this, self::ELEMENT_EMAIL, $emailOption);
        $this->_addElement($emailElement);

        $passwordOption = new \Chrome\Form\Option\Element();
        $passwordOption->setIsRequired(true)->setValidator($passwordValidator);

        $passwordElement = new \Chrome\Form\Element\Password($this, self::ELEMENT_PASSWORD, $passwordOption);
        $passwordElementSecond = new \Chrome\Form\Element\Password($this, self::ELEMENT_PASSWORD_2, $passwordOption);

        $this->_addElement($passwordElement);
        $this->_addElement($passwordElementSecond);

        $nicknameOption = new \Chrome\Form\Option\Element();
        $nicknameOption->setIsRequired(true)->setValidator($nicknameValidator)->setConversion($nameConverter);

        $nicknameElement = new \Chrome\Form\Element\Text($this, self::ELEMENT_NICKNAME, $nicknameOption);
        $this->_addElement($nicknameElement);

        $storeHandler = new \Chrome\Form\Handler\Store($storageSession, new \Chrome\Form\Option\Storage(), array(self::ELEMENT_NICKNAME, self::ELEMENT_BIRTHDAY, self::ELEMENT_EMAIL));
        $this->setAttribute(self::ATTRIBUTE_STORE, $storeHandler);
    }
}

namespace Chrome\View\Form\Module\User\Register;

use \Chrome\Form\Module\User\Register\StepOne as StepOneForm;
use \Chrome\Form\Module\User\Register\StepTwo as StepTwoForm;

class StepOne extends \Chrome\View\Form\AbstractForm
{
    protected function _modifyElementOption(\Chrome\Form\Element\BasicElement_Interface $formElement, \Chrome\View\Form\Option\BasicElement_Interface $viewOption)
    {
        $lang = $this->_viewContext->getLocalization()->getTranslate();

        switch($formElement->getID())
        {
            case StepOneForm::ELEMENT_ACCEPT:
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('accepted' => $lang->get('rules_agree'))));
                    break;
                    // viewOption->setLabelPosition($viewOption::LABEL_POSITION_BEHIND);
                }
            case StepOneForm::ELEMENT_SUBMIT:
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
            case StepTwoForm::ELEMENT_SUBMIT:
                {
                    $formElement->getOption()->setAllowedValue($lang->get('register'));
                    break;
                    // viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('accepted' => $lang->get('rules_agree'))));
                }
            case StepTwoForm::ELEMENT_BACKWARD:
                {
                    #$viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('accepted' => $lang->get('rules_agree'))));
                    break;
                }
            case StepTwoForm::ELEMENT_EMAIL:
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array(StepTwoForm::ELEMENT_EMAIL => $lang->get('email'))));
                    break;
                }
            case StepTwoForm::ELEMENT_PASSWORD:
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array(StepTwoForm::ELEMENT_PASSWORD => $lang->get('password'))));
                    break;
                }
            case StepTwoForm::ELEMENT_PASSWORD_2:
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array(StepTwoForm::ELEMENT_PASSWORD_2 => $lang->get('password_confirm'))));
                    break;
                }
            case StepTwoForm::ELEMENT_NICKNAME:
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array(StepTwoForm::ELEMENT_NICKNAME => $lang->get('nickname'))));
                    break;
                }
            case StepTwoForm::ELEMENT_BIRTHDAY:
                {
                    $viewOption->setLabel(new \Chrome\View\Form\Option\Label(array(StepTwoForm::ELEMENT_BIRTHDAY => $lang->get('birthday'))));
                    $viewOption->setDefaultInput(new \DateTime());
                    break;
                }
            case StepTwoForm::ELEMENT_CAPTCHA:
                {
                    #$viewOption->setLabel(new \Chrome\View\Form\Option\Label(array('captcha' => $lang->get('captcha'))));
                    break;
                }
        }

        return $viewOption;
    }
}
