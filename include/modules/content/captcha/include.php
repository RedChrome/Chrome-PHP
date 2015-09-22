<?php

namespace Chrome\Form\Module\Captcha;

class Captcha extends \Chrome\Form\AbstractForm
{
    protected function _init()
    {
        $this->_id = 'Captcha_Test';
        $this->setAttribute(self::ATTRIBUTE_NAME, $this->_id);
        $this->setAttribute(self::ATTRIBUTE_METHOD, self::CHROME_FORM_METHOD_POST);
        $this->setAttribute(self::ATTRIBUTE_ID, $this->_id);

        $formOption = new \Chrome\Form\Option\Element\Form($this->_getFormStorage());
        $formOption->setMaxAllowedTime(300)->setMinAllowedTime(1);
        $formElement = new \Chrome\Form\Element\Form($this, $this->_id, $formOption);
        $this->_addElement($formElement);

        $submitOption = new \Chrome\Form\Option\Element();
        $submitOption->setIsRequired(true)->setAllowedValue('test it!');
        $submitElement = new \Chrome\Form\Element\Submit($this, 'submit', $submitOption);

        $buttonsOption = new \Chrome\Form\Option\Element\Buttons();
        $buttonsOption->attach($submitElement);
        $buttonsElement = new \Chrome\Form\Element\Buttons($this, 'buttons', $buttonsOption);
        $this->_addElement($buttonsElement);

        $captchaOption = new \Chrome\Form\Option\Element\Captcha($this);
        $captchaElement = new \Chrome\Form\Element\Captcha($this, 'captcha', $captchaOption);
        $this->_addElement($captchaElement);
    }
}

namespace Chrome\View\Form\Module\Captcha;

class Captcha extends \Chrome\View\Form\AbstractForm
{

}